<?php

namespace AVHolodnyak\AssetsBusterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration class.
 *
 * @author Andrey H\olodnyak <andrey.holodnyak@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    private $extensionAlias;

    /**
     * Configuration constructor.
     *
     * @param string $extensionAlias Alias of the buster extension
     */
    public function  __construct($extensionAlias)
    {
        $this->extensionAlias = $extensionAlias;
    }

    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->extensionAlias);

        $rootNode
            ->children()
                ->scalarNode("busters_path")
                    ->isRequired()
                ->end()
                ->scalarNode("version_format")
                    ->defaultValue("%%s?%%s")
                ->end()
            ->end();

        return $treeBuilder;
    }
}
