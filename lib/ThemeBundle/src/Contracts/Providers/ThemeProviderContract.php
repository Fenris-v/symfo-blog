<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\Contracts\Providers;

use Fenris\ThemeBundle\Dto\ThemeDto;

interface ThemeProviderContract
{
    public function getThemes(): ThemeDto;
}
