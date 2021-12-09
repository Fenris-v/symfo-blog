<?php

namespace App\Service;

use App\Dto\ArticleGeneratorDto;
use App\Entity\GeneratorHistory as GeneratorHistoryEntity;
use App\Entity\User;
use App\Enums\Subscription as SubscriptionEnum;
use Exception;
use Fenris\ThemeBundle\ThemeProvider;
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
        Subscription $subscriptionService,
        Security $security,
        private GeneratorHistory $generatorHistory,
        private RestrictionService $restrictionService,
        private ThemeProvider $themeProvider
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
        $this->articleGeneratorDto->setTitle(
            isset($data['title']) && $data['title']
                ? $data['title'] : $this->themeProvider->getTitle($this->articleGeneratorDto->getTheme())
        );

        $keywords = [];
        if (!isset($data['keyword']) || !$data['keyword']) {
            $keywords = $this->themeProvider->getKeywords($this->articleGeneratorDto->getTheme());
        }
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

        return $this;
    }

    /**
     * @return GeneratorHistoryEntity
     * @throws Exception
     */
    public function getArticle(): GeneratorHistoryEntity
    {
        $paragraphs = $this->getParagraphs();
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
                (int)$this->articleGeneratorDto->getWordCountField()[array_key_first(
                    $this->articleGeneratorDto->getWordCountField()
                )] ?: 0
            );

            return $this;
        }

        foreach ($this->articleGeneratorDto->getWordField() as $key => $word) {
            $this->pasteWord($word, (int)$this->articleGeneratorDto->getWordCountField()[$key] ?? 0);
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
        foreach ($paragraphs as $paragraph) {
            $html .= "<p>$paragraph</p>";
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
     * @throws Exception
     */
    private function getParagraphs(): array
    {
        // todo: заменить количество параграфов на количество модулей, после реализации самих модулей
        $paragraphsCount = $this->articleGeneratorDto->getSizeFrom() ?? 1;

        if (!is_null($this->articleGeneratorDto->getSizeFrom()) && !is_null($this->articleGeneratorDto->getSizeTo())) {
            $paragraphsCount = rand($this->articleGeneratorDto->getSizeFrom(), $this->articleGeneratorDto->getSizeTo());
        }

        return $this->themeProvider->getParagraphs(
            $this->articleGeneratorDto->getTheme(),
            $paragraphsCount
        );
    }

    private function setDeclinationToDto(array $data): void
    {
        if (isset($data['keyword']) && $data['keyword'] && isset($data['declination']) && $data['declination']) {
            $this->articleGeneratorDto->setDeclination($data['declination']);
            return;
        }

        if ($this->articleGeneratorDto !== null) {
            $keywords = $this->themeProvider->getKeywords($this->articleGeneratorDto->getTheme());
            array_shift($keywords);
            $this->articleGeneratorDto->setDeclination($keywords);
        }
    }
}
