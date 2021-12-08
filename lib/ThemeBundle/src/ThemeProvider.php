<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle;

use Fenris\ThemeBundle\Contracts\Providers\ThemeProviderContract;
use Fenris\ThemeBundle\Dto\ThemeDto;

class ThemeProvider
{
    public function __construct(private ThemeProviderContract $ThemeProvider)
    {
    }

    public function getThemes(): ThemeDto
    {
        return $this->ThemeProvider->getThemes();
    }
}
