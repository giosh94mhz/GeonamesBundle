<?php
namespace Giosh94mhz\GeonamesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GeocoderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (! $container->hasDefinition('giosh94mhz_geonames.import.director'))
            return;

        if (! $container->hasDefinition('bazinga_geocoder.geocoder')) {
            return;
        }

        if (! $container->getParameter('giosh94mhz_geonames.geocoder.enabled'))
            return;

        $geocoder = $container->getDefinition('bazinga_geocoder.geocoder');
        $geocoder
            ->addMethodCall('setResultFactory',array(
                new Reference('giosh94mhz_geonames.geocoder.result_factory')
            ))
            ->addMethodCall('using',array(
                'persistent_geonames'
            ))
        ;

        $ipProviderName = $container->getParameter('giosh94mhz_geonames.geocoder.ip_provider');
        if ($ipProviderName !== null) {
            $ipProvider = $container->getDefinition('bazinga_geocoder.provider.' . $ipProviderName);
            $container
                ->getDefinition('giosh94mhz_geonames.geocoder.persistent_geonames_provider')
                ->addMethodCall('setIpProvider', array(
                    $ipProvider
                ))
            ;
        }
    }
}
