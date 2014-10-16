<?php

namespace Giosh94mhz\GeonamesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Giosh94mhz\GeonamesBundle\Geocoder\PersistentGeonamesProvider;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('giosh94mhz_geonames');

        /* @var $rootNode \Symfony\Component\Config\Definition\Builder\NodeBuilder */
        $rootNode
            ->info('Default configuration import all continents, countries and cities with population above 15000 from all countries')
            ->children()
                ->arrayNode('orm')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('object_manager_name')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue('default')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('download')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('adapter')
                            ->values(array('curl', 'guzzle'))
                            ->defaultValue('curl')
                        ->end()
                        ->scalarNode('directory')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.cache_dir%/giosh94mhz_geonames')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('feature')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('locale')
                            ->defaultNull()
                        ->end()
                        ->arrayNode('include')
                            ->prototype('scalar')->end()
                            ->defaultValue(array('A.*','P.*'))
                        ->end()
                        ->arrayNode('exclude')
                            ->prototype('scalar')->end()
                            ->defaultValue(array('H.*','*.*H*'))
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('toponym')
                    ->beforeNormalization()
                        ->always()
                        ->then(function ($value) {
                            if (!empty($value['all'])) {
                                $value['cities'] = false;
                                $value['countries'] = array();
                            }

                            return $value;
                        })
                    ->end()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('all')
                            ->info('All include all toponym from all countries (overrides cities/countries configurations)')
                            ->defaultFalse()
                        ->end()
                        ->arrayNode('countries')
                            ->info('List of country codes to restrict import (empty means import all)')
                            ->prototype('scalar')->end()
                        ->end()
                        ->enumNode('cities')
                            ->info('Import only cities (and other toponym) with population above the specified value')
                            ->defaultValue(15000)
                            ->values(array(false, 1000, 5000, 15000))
                        ->end()
                        ->arrayNode('options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('alternate_names')
                                    ->info('Import text-only (simple) altenate names (useless if giosh94mhz_geonames.alternate_names is enabled)')
                                    ->defaultFalse()
                                ->end()
                                ->booleanNode('alternate_country_codes')
                                    ->info('Import alternate country codes other than the official one')
                                    ->defaultTrue()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('continent')
                    ->addDefaultsIfNotSet()
                    ->canBeDisabled()
                ->end()
                ->arrayNode('country')
                    ->addDefaultsIfNotSet()
                    ->canBeDisabled()
                ->end()
                ->arrayNode('admin1')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                ->end()
                ->arrayNode('admin2')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                ->end()
                ->arrayNode('alternate_names')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                ->end()
                ->arrayNode('hierarchy')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                ->end()
                ->arrayNode('geocoder')
                    ->addDefaultsIfNotSet()
                    ->canBeDisabled()
                    ->children()
                        ->scalarNode('ip_provider')
                            ->info('Name of the Geocoder provider to use to resolve IP addresses')
                            ->defaultValue(false)
                        ->end()
                        ->arrayNode('search_distances')
                            ->info('Progressive max distances to use when reverse geocoding')
                            ->prototype('scalar')->end()
                            ->defaultValue(array(8, 16, 64))
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
