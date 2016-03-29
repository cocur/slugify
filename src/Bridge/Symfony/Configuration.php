<?php

/*
 * This file is part of the cocur/slugify package.
 *
 * (c) Enrico Stahn <enrico.stahn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocur\Slugify\Bridge\Symfony;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cocur_slugify');

        $rootNode
            ->children()
                ->booleanNode('lowercase')->defaultTrue()->end()
                ->scalarNode('regexp')->end()
                ->arrayNode('rulesets')->prototype('scalar')->end()
            ->end();

        return $treeBuilder;
    }
}