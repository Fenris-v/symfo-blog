<?php

namespace App\Dto;

class ArticleGeneratorDto extends AbstractDto
{
    protected ?string $theme = null;
    protected ?string $title = null;
    protected ?string $keyword = null;
    protected ?array $declination = null;
    protected ?int $sizeFrom = null;
    protected ?int $sizeTo = null;
    protected ?array $wordField = null;
    protected ?array $wordCountField = null;
    protected array $images = [];

    /**
     * @return string|null
     */
    public function getTheme(): ?string
    {
        return $this->theme;
    }

    /**
     * @param string|null $theme
     */
    public function setTheme(?string $theme): void
    {
        $this->theme = $theme;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * @param string|null $keyword
     */
    public function setKeyword(?string $keyword): void
    {
        $this->keyword = $keyword;
    }

    /**
     * @return array|null
     */
    public function getDeclination(): ?array
    {
        return $this->declination;
    }

    /**
     * @param array|null $declination
     */
    public function setDeclination(?array $declination): void
    {
        $this->declination = $declination;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return int|null
     */
    public function getSizeFrom(): ?int
    {
        return $this->sizeFrom;
    }

    /**
     * @param int|null $sizeFrom
     */
    public function setSizeFrom(?int $sizeFrom): void
    {
        $this->sizeFrom = $sizeFrom;
    }

    /**
     * @return int|null
     */
    public function getSizeTo(): ?int
    {
        return $this->sizeTo;
    }

    /**
     * @param int|null $sizeTo
     */
    public function setSizeTo(?int $sizeTo): void
    {
        $this->sizeTo = $sizeTo;
    }

    /**
     * @return array|null
     */
    public function getWordField(): ?array
    {
        return $this->wordField;
    }

    /**
     * @param array|null $wordField
     */
    public function setWordField(?array $wordField): void
    {
        $this->wordField = $wordField;
    }

    /**
     * @return array|null
     */
    public function getWordCountField(): ?array
    {
        return $this->wordCountField;
    }

    /**
     * @param array|null $wordCountField
     */
    public function setWordCountField(?array $wordCountField): void
    {
        $this->wordCountField = $wordCountField;
    }

    /**
     * @return ?array
     */
    public function getKeywordWithDeclination(): ?array
    {
        if (!$this->declination) {
            return [$this->keyword];
        }

        $keyword = $this->declination;
        array_unshift($keyword, $this->keyword);

        return $keyword;
    }
}
