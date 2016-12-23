<?php

namespace AVHolodnyak\AssetsBusterBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Bundle di-extension class.
 *
 * @author Andrey Holodnyak <andrey.holodnyak@gmail.com>
 */
class AssetsBusterExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration($this->getAlias());
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            "assets_buster.busters_path",
            $config["busters_path"]
        );
        $container->setParameter(
            "assets_buster.version_format",
            $config["version_format"]
        );

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__."/../Resources/config")
        );
        $loader->load("services.yml");
    }

    /**
     * @inheritdoc
     */
    public function getAlias()
    {
        return "assets_buster";
    }

    /**
     * @inheritdoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig("framework", array(
            "assets" => array(
                "version_strategy" => "avholodnyak.assets_buster.version_strategy",
            )
        ));
    }
}