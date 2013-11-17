<?php

namespace Giosh94mhz\GeonamesBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Giosh94mhz\GeonamesBundle\DependencyInjection\Compiler\ImportAllCompilerPass;

class Giosh94mhzGeonamesBundle extends Bundle
{
    public function boot()
    {
        if (!Type::hasType('string_simple_array')) {
            // this should be optional if using a different ORM
            Type::addType('string_simple_array', 'Giosh94mhz\GeonamesBundle\Doctrine\Types\StringSimpleArrayType');
        }
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ImportAllCompilerPass());
    }
}
