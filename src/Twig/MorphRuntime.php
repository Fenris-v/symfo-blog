<?php

namespace App\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class MorphRuntime implements RuntimeExtensionInterface
{
    /**
     * @param $value
     * @param $key
     * @return string
     */
    public function replaceKeywords($value, $key): string
    {
        return $value[$key] ?? $value[0] ?? '';
    }
}
