<?php

namespace App\Service;

use App\Dto\ArticleGeneratorDto;
use App\Dto\ModuleDto;
use App\Entity\GeneratorHistory as GeneratorHistoryEntity;
use App\Entity\Module;
use App\Entity\User;
use App\Enums\Subscription as SubscriptionEnum;
use App\Repository\ModuleRepository;
use Exception;
use Fenris\ThemeBundle\ThemeProvider;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

class ArticleGenerator
{
    private ?\App\Entity\Subscription $subscription;
    private ?User $user;
    private ?ArticleGeneratorDto $articleGeneratorDto = null;
    private ?ModuleService $modules = null;
    private string $article = '';
    private array $images = [];

    public function __construct(
        Subscription $subscriptionService,
        Security $security,
        private GeneratorHistory $generatorHistory,
        private RestrictionService $restrictionService,
        private ThemeProvider $themeProvider,
        private ModuleRepository $moduleRepository
    ) {
        $this->user = $security->getUser();
        $this->subscription = $subscriptionService->getSubscription($this->user);
    }

    public function getArticleGeneratorDto(): ?ArticleGeneratorDto
    {
        return $this->articleGeneratorDto;
    }

    public function createDto(array $data): static
    {
        $this->themeProvider->setTheme(new $data['theme']);

        $this->articleGeneratorDto = new ArticleGeneratorDto();
        $this->articleGeneratorDto->setTheme(isset($data['theme']) && $data['theme'] ? $data['theme'] : null);
        $this->articleGeneratorDto->setTitle(
            isset($data['title']) && $data['title']
                ? $data['title'] : $this->themeProvider->getTitle()
        );

        $keywords = isset($data['keyword']) && $data['keyword']
            ? $data['keyword'] : $this->themeProvider->getKeywords();
        $this->articleGeneratorDto->setKeyword(
            isset($data['keyword']) && $data['keyword']
                ? $data['keyword'] : $keywords[0]
        );

        $this->articleGeneratorDto->setSizeFrom(
            isset($data['sizeFrom']) && $data['sizeFrom']
                ? $data['sizeFrom'] : null
        );

        $this->articleGeneratorDto->setSizeTo(isset($data['sizeTo']) && $data['sizeTo'] ? $data['sizeTo'] : null);
        $this->articleGeneratorDto->setWordField(
            isset($data['wordField']) && $data['wordField']
                ? $data['wordField'] : null
        );

        $this->articleGeneratorDto->setWordCountField(
            isset($data['wordCountField']) && $data['wordCountField']
                ? $data['wordCountField'] : null
        );

        if ($this->restrictionService->canHaveDeclinations()) {
            $this->setDeclinationToDto($data);
        } else {
            $this->articleGeneratorDto->setDeclination(null);
        }

        $this->articleGeneratorDto->setImages($data['images']);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getArticle(): GeneratorHistoryEntity
    {
        $this->generateArticle();
        return $this->generatorHistory->save($this->article, $this->articleGeneratorDto);
    }

    /**
     * @throws Exception
     */
    public function getDemoArticle(): string
    {
        $this->generateArticle();
        return $this->article;
    }

    /**
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function setRenderedText(Environment $twig, GeneratorHistoryEntity $article): void
    {
        $keywords = $article->getProps()['declination'] ?? [];
        array_unshift($keywords, $article->getProps()['keyword']);

        $article->setArticle(
            $twig->createTemplate($article->getArticle())
                ?->render(['keyword' => $keywords])
        );
    }

    /**
     * @throws Exception
     */
    private function generateArticle(): void
    {
        $this->modules = $this->getModules();

        $paragraphs = $this->getParagraphs($this->modules->getLength());
        $paragraphs = $this->pasteWords($paragraphs);
        $this->modules->setParagraphs($paragraphs);
        $this->renderHtml();
    }

    /**
     * @throws Exception
     */
    private function pasteWords(array $paragraphs): array
    {
        if ($this->articleGeneratorDto?->getWordField() === null) {
            return $paragraphs;
        }

        if ($this->subscription->getSlug() !== SubscriptionEnum::Pro->value) {
            return $this->pasteWord(
                $paragraphs,
                $this->articleGeneratorDto->getWordField()[array_key_first($this->articleGeneratorDto->getWordField())],
                (int)$this->articleGeneratorDto->getWordCountField()[array_key_first(
                    $this->articleGeneratorDto->getWordCountField()
                )] ?: 0
            );
        }

        foreach ($this->articleGeneratorDto->getWordField() as $key => $word) {
            $paragraphs = $this->pasteWord(
                $paragraphs,
                $word,
                (int)$this->articleGeneratorDto->getWordCountField()[$key] ?? 0
            );
        }

        return $paragraphs;
    }

    /**
     * @throws Exception
     */
    private function pasteWord(array $paragraphs, string $word, int $count): array
    {
        if (empty($word)) {
            return $paragraphs;
        }

        $paragraphs = implode('||', $paragraphs);
        $text = explode(' ', $paragraphs);
        $length = count($text) - 1;

        if ($count >= $length) {
            throw new Exception('Задано слишком много повторений слова');
        }

        for ($i = 0; $i < $count; $i++) {
            $key = rand(1, $length);

            while (str_contains($text[$key], $word)) {
                $key = rand(1, $length);
            }

            $text[$key] = "$word $text[$key]";
        }

        $paragraphs = implode(' ', $text);
        return explode('||', $paragraphs);
    }

    /**
     * @throws Exception
     */
    private function getParagraphs(int $length): array
    {
        return $this->themeProvider->getParagraphs($length);
    }

    private function setDeclinationToDto(array $data): void
    {
        if (isset($data['keyword']) && $data['keyword'] && isset($data['declination']) && $data['declination']) {
            $this->articleGeneratorDto->setDeclination($data['declination']);
            return;
        }

        if ($this->articleGeneratorDto !== null) {
            $keywords = $this->themeProvider->getKeywords();
            array_shift($keywords);
            $this->articleGeneratorDto->setDeclination($keywords);
        }
    }

    private function getModules(): ModuleService
    {
        $length = $this->articleGeneratorDto?->getSizeFrom() ?? 1;

        $modules = $this->moduleRepository
            ->getFromAccessibleModules($length, $this->user?->getId());

        $modulesData = [];
        /** @var Module $module */
        foreach ($modules as $module) {
            $modulesData[] = new ModuleDto($module->getTemplate());
        }

        return new ModuleService($modulesData);
    }

    private function renderHtml(): void
    {
        $this->images = $this->articleGeneratorDto->getImages();

        /** @var ModuleDto $module */
        foreach ($this->modules->getModules() as $module) {
            $this->replaceParagraphs($module);
            $this->replaceImage($module);
            $this->replaceTitle($module);

            $this->article .= $module->getTemplate();
        }
    }

    private function replaceParagraphs(ModuleDto $module): void
    {
        if (!(bool)preg_match('/({{\s?paragraphs?\s?}})/', $module->getTemplate())) {
            return;
        }

        $module->setTemplate(
            preg_replace(
                '/({{\s?paragraphs?\s?}})/',
                $this->getText($module),
                $module->getTemplate()
            )
        );
    }

    private function replaceImage(ModuleDto $module): void
    {
        if (!(bool)preg_match('/({{\s?imageSrc\s?}})/', $module->getTemplate())) {
            return;
        }

        $image = $this->getImage();

        $module->setTemplate(
            preg_replace(
                '/({{\s?imageSrc\s?}})/',
                $image,
                $module->getTemplate()
            )
        );
    }

    private function replaceTitle(ModuleDto $module): void
    {
        if (!(bool)preg_match('/({{\s?title\s?}})/', $module->getTemplate())) {
            return;
        }

        $module->setTemplate(
            preg_replace(
                '/({{\s?title\s?}})/',
                $this->themeProvider->getTitle(),
                $module->getTemplate()
            )
        );
    }

    private function getText(ModuleDto $moduleDto): string
    {
        if (preg_match('/({{\s?paragraphs\s?}})/', $moduleDto->getTemplate())) {
            $text = '';
            foreach ($moduleDto->getParagraphs() as $paragraph) {
                $text .= "<p>$paragraph</p>";
            }

            return $text;
        }

        return $moduleDto->getParagraphs()[array_key_first($moduleDto->getParagraphs())];
    }

    private function getImage()
    {
        if (!empty($this->images)) {
            return array_shift($this->images);
        }

        return $this->themeProvider->getImage();
    }
}
