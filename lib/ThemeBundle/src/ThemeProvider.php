<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle;

use Fenris\ThemeBundle\Contracts\Providers\ThemeProviderContract;

class ThemeProvider
{
    public function __construct(private ThemeProviderContract $ThemeProvider)
    {
    }

    public function addThemes()
    {
        $this->ThemeProvider->addThemes();
    }
}
