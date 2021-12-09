<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle;

use Fenris\ThemeBundle\DependencyInjection\ThemeProviderExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ThemeProviderBundle extends Bundle
{
    public function getContainerExtension(): ThemeProviderExtension
    {
        if (null === $this->extension) {
            $this->extension = new ThemeProviderExtension();
        }

        return $this->extension;
    }
}
