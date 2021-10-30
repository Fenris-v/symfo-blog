<?php

namespace App\Service;

use App\Entity\TextTemplate;
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

    public function __construct(
        private TextTemplateRepository $textTemplateRepository,
        private ThemeRepository $themeRepository,
        Subscription $subscription,
        Security $security,
        private GeneratorHistory $generatorHistory
    ) {
        $this->subscription = $subscription->getSubscription($security->getUser());
    }

    /**
     * @param array $data
     * @return string|null
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function getArticle(array $data): ?string
    {
        /** @var Theme $theme */
        $theme = $this->themeRepository->getBySlug($data['theme']);
        if (empty($theme)) {
            throw new Exception('Тема не найдена, проверьте корректность ввода');
        }

        $paragraphs = $this->getParagraphs($theme, $data);
        $article = $this->getHtml($paragraphs);
        $article = $this->pasteWords($article, $data);
        $article = $this->setTitle($article, $data);

        $this->generatorHistory->save($article, $data);

        return $article;
    }

    /**
     * @param array $data
     * @param string $article
     * @return string
     * @throws Exception
     */
    private function pasteWords(string $article, array $data = []): string
    {
        if (!isset($data['wordField'])) {
            return $article;
        }

        if ($this->subscription->getSlug() !== \App\Entity\Subscription::LEVELS['max']) {
            return $this->pasteWord(
                $article,
                $data['wordField'][array_key_first($data['wordField'])],
                $data['wordCountField'][array_key_first($data['wordCountField'])] ?? 0
            );
        }

        foreach ($data['wordField'] as $key => $word) {
            $article = $this->pasteWord(
                $article,
                $word,
                $data['wordCountField'][$key] ?? 0
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
     * @param array $data
     * @return string
     */
    private function setTitle(string $article, array $data = []): string
    {
        if (!isset($data['title'])) {
            return $article;
        }

        return "<h1>{$data['title']}</h1>$article";
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
     * @param Theme $theme
     * @param array $data
     * @return array
     * @throws Exception
     */
    private function getParagraphs(Theme $theme, array $data = []): array
    {
        // todo: заменить количество параграфов на количество модулей, после реализации самих модулей
        $paragraphs = $this->textTemplateRepository
            ->getRandomParagraphs(
                rand($data['sizeFrom'], $data['sizeTo']),
                $theme->getId()
            );

        if (is_null($paragraphs)) {
            throw new Exception('Не найдено ни одного параметра подходящей темы');
        }

        return $paragraphs;
    }
}
