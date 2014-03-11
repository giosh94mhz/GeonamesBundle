<?php
namespace Giosh94mhz\GeonamesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ImportAllCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (! $container->hasDefinition('giosh94mhz_geonames.import.director'))
            return;

        $importSteps = array();
        foreach ($container->findTaggedServiceIds('giosh94mhz_geonames.import.all_steps') as $stepId => $attributes) {
            $importSteps[] = new Reference($stepId);
        }

        $container->setDefinition('giosh94mhz_geonames.import.all_steps', new Definition(
            'Doctrine\Common\Collections\ArrayCollection',
            array($importSteps)
        ));
    }
}
