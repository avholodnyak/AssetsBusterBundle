<?php

namespace AVHolodnyak\AssetsBusterBundle\Tests\Functional\App;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;

use AVHolodnyak\AssetsBusterBundle\AssetsBusterBundle;


/**
 * Application kernel class for functional testing.
 *
 * @author Andrey Holodnyak <andrey.holodnyak@gmail.com>
 */
class AppKernel extends Kernel
{
    /**
     * @var bool
     */
    private $customVersionFormat = false;

    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new AssetsBusterBundle(),
        ];
    }

    /**
     * Forces application to use custom version format for the assets buster bundle
     */
    public function useCustomVersionFormat()
    {
        $this->customVersionFormat = true;
    }

    /**
     * Loads the container configuration.
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $config = "config-default-version-format.yml";
        if ($this->customVersionFormat) {
            $config = "config-custom-version-format.yml";
        }
        $loader->load(__DIR__ . "/config/${config}");
    }
}
