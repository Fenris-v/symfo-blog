<?php

namespace App\Service;

use App\Dto\ArticleGeneratorDto;
use App\Entity\TextTemplate;
use App\Entity\User;
use App\Enums\Subscription as SubscriptionEnum;
use App\Repository\TextTemplateRepository;
use App\Repository\ThemeRepository;
use App\Entity\Theme;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\Security\Core\Security;

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

    public function __construct(
        private TextTemplateRepository $textTemplateRepository,
        private ThemeRepository $themeRepository,
        Subscription $subscriptionService,
        Security $security,
        private GeneratorHistory $generatorHistory
    ) {
        $this->user = $security->getUser();
        $this->subscription = $subscriptionService->getSubscription($this->user);
    }

    /**
     * @param ArticleGeneratorDto $dto
     * @return string
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function getArticle(ArticleGeneratorDto $dto): string
    {
        /** @var Theme $theme */
        $theme = $this->themeRepository->getBySlug($dto->getTheme());
        if (empty($theme)) {
            throw new Exception('Тема не найдена, проверьте корректность ввода');
        }

        $paragraphs = $this->getParagraphs($theme->getId(), $dto);
        $article = $this->getHtml($paragraphs);
        $article = $this->pasteWords($article, $dto);

        $this->generatorHistory->save($article, $dto);

        return $article;
    }

    /**
     * @param string $article
     * @param ArticleGeneratorDto $dto
     * @return string
     * @throws Exception
     */
    private function pasteWords(string $article, ArticleGeneratorDto $dto): string
    {
        if (!$dto->getWordField()) {
            return $article;
        }

        if ($this->subscription->getSlug() !== SubscriptionEnum::Pro->value) {
            return $this->pasteWord(
                $article,
                $dto->getWordField()[array_key_first($dto->getWordField())],
                $dto->getWordCountField()[array_key_first($dto->getWordCountField())] ?: 0
            );
        }

        foreach ($dto->getWordField() as $key => $word) {
            $article = $this->pasteWord(
                $article,
                $word,
                $data->wordCountField[$key] ?? 0
            );
        }

        return $article;
    }

    /**
     * @param array $paragraphs
     * @return string
     */
    private function getHtml(array $paragraphs): string
    {
        $html = '';
        /** @var TextTemplate $paragraph */
        foreach ($paragraphs as $paragraph) {
            $html .= "<p>{$paragraph->getTemplate()}</p>";
        }

        return $html;
    }

    /**
     * @param string $article
     * @param string $word
     * @param int $count
     * @return string
     * @throws Exception
     */
    private function pasteWord(string $article, string $word, int $count): string
    {
        if (empty($word)) {
            return $article;
        }

        $text = explode(' ', $article);
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

        return implode(' ', $text);
    }

    /**
     * @param int $themeId
     * @param ArticleGeneratorDto $dto
     * @return array
     * @throws Exception
     */
    private function getParagraphs(int $themeId, ArticleGeneratorDto $dto): array
    {
        // todo: заменить количество параграфов на количество модулей, после реализации самих модулей
        $paragraphsCount = $dto->getSizeFrom() ?? 1;
        if (!is_null($dto->getSizeFrom()) && !is_null($dto->getSizeTo())) {
            $paragraphsCount = rand($dto->getSizeFrom(), $dto->getSizeTo());
        }

        $paragraphs = $this->textTemplateRepository
            ->getRandomParagraphs($paragraphsCount, $themeId);

        if (is_null($paragraphs)) {
            throw new Exception('Не найдено ни одного параметра подходящей темы');
        }

        return $paragraphs;
    }
}
