<?php

namespace AVHolodnyak\AssetsBusterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use AVHolodnyak\AssetsBusterBundle\DependencyInjection\AssetsBusterExtension;

/**
 * This bundle provides support for cache busting using buster files.
 * It uses custom version strategies feature that was introduced in Symfony 3.1.
 *
 * @author Andrey Holodnyak <andrey.holodnyak@gmail.com>
 * @package AssetsBusterBundle
 */
class AssetsBusterBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function getContainerExtension()
    {
        return new AssetsBusterExtension();
    }
}
