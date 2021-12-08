<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\Providers;

use Fenris\ThemeBundle\Contracts\Providers\ThemeProviderContract;
use Fenris\ThemeBundle\Dto\ThemeDto;

class SomeThemeProvider implements ThemeProviderContract
{
    public function getThemes(): ThemeDto
    {
        return new ThemeDto();
    }
}
