<?php

declare(strict_types=1);

namespace App\DDDBundle;

use App\DDDBundle\DependencyInjection\DDDExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DDDBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new DDDExtension();
        }

        return $this->extension;
    }
}
