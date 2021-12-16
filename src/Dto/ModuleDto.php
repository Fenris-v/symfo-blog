<?php

declare(strict_types=1);

namespace App\Dto;

use App\Contracts\ModuleLengthContract;

class ModuleDto implements ModuleLengthContract
{
    private int $length;

    public function __construct(
        private string $template,
        private ?array $paragraphs = null
    ) {
        $this->length = $this->getLengthByTemplate();
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getParagraphs(): ?array
    {
        return $this->paragraphs;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function setParagraphs(?array $paragraphs): void
    {
        $this->paragraphs = $paragraphs;
    }

    private function getLengthByTemplate(): int
    {
        if (preg_match('/({{\s?paragraphs\s?}})/', $this->template)) {
            return rand(1, 3);
        }

        if (preg_match('/({{\s?paragraph\s?}})/', $this->template)) {
            return 1;
        }

        return 0;
    }
}
