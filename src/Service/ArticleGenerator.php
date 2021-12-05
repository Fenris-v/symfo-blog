<?php

namespace App\Service;

use App\Dto\ArticleGeneratorDto;
use App\Entity\GeneratorHistory as GeneratorHistoryEntity;
use App\Entity\TextTemplate;
use App\Entity\Theme;
use App\Entity\User;
use App\Enums\Subscription as SubscriptionEnum;
use App\Repository\TextTemplateRepository;
use App\Repository\ThemeRepository;
use Exception;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

class ArticleGenerator
{
    // Для генерации статьи доступны следующие плейсхолдеры:
    // {{ keyword }} - для вставки ключевого слова
    // {{ keyword|morph(number) }} - для вставки ключевого слова в определенной словоформе
    // {{ title }} - для вставки заголовка модуля (подзаголовки)
    // {{ paragraph }} - для вставки текста одного абзаца (без тега <p>)
    // {{ paragraphs }} - для вставки случайного количества параграфов от 1 до 3, при этом теги <p> устанавливаются автоматически
    // {{ imageSrc }} - путь к картинке, предполагается использовать внутри тегов <img>

    private ?\App\Entity\Subscription $subscription;
    private ?User $user;
    private ?ArticleGeneratorDto $articleGeneratorDto = null;
    private ?string $article = null;

    public function __construct(
        private TextTemplateRepository $textTemplateRepository,
        private ThemeRepository $themeRepository,
        Subscription $subscriptionService,
        Security $security,
        private GeneratorHistory $generatorHistory,
        private RestrictionService $restrictionService
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
        $this->articleGeneratorDto = new ArticleGeneratorDto();
        $this->articleGeneratorDto->setTheme(isset($data['theme']) && $data['theme'] ? $data['theme'] : null);
        $this->articleGeneratorDto->setTitle(isset($data['title']) && $data['title'] ? $data['title'] : null);
        $this->articleGeneratorDto->setKeyword(isset($data['keyword']) && $data['keyword'] ? $data['keyword'] : null);
        $this->articleGeneratorDto->setSizeFrom(
            isset($data['sizeFrom']) && $data['sizeFrom'] ? $data['sizeFrom'] : null
        );
        $this->articleGeneratorDto->setSizeTo(isset($data['sizeTo']) && $data['sizeTo'] ? $data['sizeTo'] : null);
        $this->articleGeneratorDto->setWordField(
            isset($data['wordField']) && $data['wordField'] ? $data['wordField'] : null
        );

        $this->articleGeneratorDto->setWordCountField(
            isset($data['wordCountField']) && $data['wordCountField'] ? $data['wordCountField'] : null
        );

        if ($this->restrictionService->canHaveDeclinations()) {
            $this->articleGeneratorDto->setDeclination(
                isset($data['declination']) && $data['declination'] ? $data['declination'] : null
            );
        } else {
            $this->articleGeneratorDto->setDeclination(null);
        }

        return $this;
    }

    /**
     * @return GeneratorHistoryEntity
     * @throws Exception
     */
    public function getArticle(): GeneratorHistoryEntity
    {
        $theme = $this->getTheme();

        $paragraphs = $this->getParagraphs($theme->getId());
        $this->getHtml($paragraphs)->pasteWords();

        if ($this->user) {
            return $this->generatorHistory->save($this->article, $this->articleGeneratorDto);
        }

        return $this->generatorHistory->getEntity($this->article, $this->articleGeneratorDto);
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
     * @return ArticleGenerator
     * @throws Exception
     */
    private function pasteWords(): static
    {
        if (!$this->articleGeneratorDto->getWordField()) {
            return $this;
        }

        if ($this->subscription->getSlug() !== SubscriptionEnum::Pro->value) {
            $this->pasteWord(
                $this->articleGeneratorDto->getWordField()[array_key_first($this->articleGeneratorDto->getWordField())],
                $this->articleGeneratorDto->getWordCountField()[array_key_first(
                    $this->articleGeneratorDto->getWordCountField()
                )] ?: 0
            );

            return $this;
        }

        foreach ($this->articleGeneratorDto->getWordField() as $key => $word) {
            $this->pasteWord($word, $this->articleGeneratorDto->getWordCountField()[$key] ?? 0);
        }

        return $this;
    }

    /**
     * @param array $paragraphs
     * @return ArticleGenerator
     */
    private function getHtml(array $paragraphs): static
    {
        $html = '';
        /** @var TextTemplate $paragraph */
        foreach ($paragraphs as $paragraph) {
            $html .= "<p>{$paragraph->getTemplate()}</p>";
        }

        $this->article = $html;
        return $this;
    }

    /**
     * @param string $word
     * @param int $count
     * @return ArticleGenerator
     * @throws Exception
     */
    private function pasteWord(string $word, int $count): static
    {
        if (empty($word)) {
            return $this;
        }

        $text = explode(' ', $this->article);
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

        $this->article = implode(' ', $text);
        return $this;
    }

    /**
     * @param int $themeId
     * @return array
     * @throws Exception
     */
    private function getParagraphs(int $themeId): array
    {
        // todo: заменить количество параграфов на количество модулей, после реализации самих модулей
        $paragraphsCount = $this->articleGeneratorDto->getSizeFrom() ?? 1;
        if (!is_null($this->articleGeneratorDto->getSizeFrom()) && !is_null($this->articleGeneratorDto->getSizeTo())) {
            $paragraphsCount = rand($this->articleGeneratorDto->getSizeFrom(), $this->articleGeneratorDto->getSizeTo());
        }

        $paragraphs = $this->textTemplateRepository
            ->getRandomParagraphs($paragraphsCount, $themeId);

        if (is_null($paragraphs)) {
            throw new Exception('Не найдено ни одного параметра подходящей темы');
        }

        return $paragraphs;
    }

    public function setTitle(): static
    {
        $this->article = $this->articleGeneratorDto->getTitle()
            ? "<h1>{$this->articleGeneratorDto->getTitle()}</h1>$this->article"
            : $this->article;

        return $this;
    }

    private function getTheme(): Theme
    {
        if ($this->articleGeneratorDto->getTheme()) {
            $theme = $this->themeRepository->getBySlug($this->articleGeneratorDto->getTheme());
            if (empty($theme)) {
                throw new Exception('Тема не найдена, проверьте корректность ввода');
            }

            return $theme;
        }

        return $this->themeRepository->getRandom();
    }
}
