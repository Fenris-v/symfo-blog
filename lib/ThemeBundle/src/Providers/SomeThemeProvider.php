<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\Providers;

use Fenris\ThemeBundle\Contracts\Providers\ThemeProviderContract;

class SomeThemeProvider implements ThemeProviderContract
{
    public function addThemes()
    {
        dd(123);
    }
}
