<?php
namespace Giosh94mhz\GeonamesBundle\Tests;

use Doctrine\Tests\OrmFunctionalTestCase as DoctrineOrmFunctionalTestCase;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;

/**
 * Basic class for functional database tests
 *
 * @group functional
 */
class OrmFunctionalTestCase extends DoctrineOrmFunctionalTestCase
{
    /**
     * List of model sets and their classes.
     *
     * @var array
     */
    protected static $_modelSets = array(
        'giosh94mhz' => array(
            'Giosh94mhz\GeonamesBundle\Entity\Feature',
            'Giosh94mhz\GeonamesBundle\Entity\Language',
            'Giosh94mhz\GeonamesBundle\Entity\Toponym',
            'Giosh94mhz\GeonamesBundle\Entity\Continent',
            'Giosh94mhz\GeonamesBundle\Entity\Country',
            'Giosh94mhz\GeonamesBundle\Entity\Admin1',
            'Giosh94mhz\GeonamesBundle\Entity\Admin2',
            'Giosh94mhz\GeonamesBundle\Entity\AlternateName',
            'Giosh94mhz\GeonamesBundle\Entity\HierarchyLink',
        ),
    );

    protected function _getEntityManager($config = null, $eventManager = null)
    {
        $em = parent::_getEntityManager($config, $eventManager);
        $this->emulateBundleBoot($em);

        return $em;
    }

    private function emulateBundleBoot(EntityManager $em)
    {
        $mappingDriver  = new SimplifiedXmlDriver(array(
            __DIR__ . '/../Resources/config/doctrine' => 'Giosh94mhz\\GeonamesBundle\\Entity',
        ));

        $config = $em->getConfiguration();
        $config->setMetadataDriverImpl($mappingDriver);
        $config->addEntityNamespace('Giosh94mhzGeonamesBundle', 'Giosh94mhz\\GeonamesBundle\\Entity');

        if ($em->getConnection()->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\SqlitePlatform) {
            $config->addCustomNumericFunction('GEO_DISTANCE', 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\Sqlite\GeoDistance');
            $config->addCustomNumericFunction('GEO_DISTANCE_WITHIN', 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\Sqlite\GeoDistanceWithin');
        } else {
            $config->addCustomNumericFunction('GEO_DISTANCE', 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\GeoDistance');
            $config->addCustomNumericFunction('GEO_DISTANCE_WITHIN', 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\GeoDistanceWithin');
        }
        $config->addCustomNumericFunction('LATITUDE_WITHIN', 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\LatitudeWithin');
        $config->addCustomNumericFunction('LONGITUDE_WITHIN', 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\LongitudeWithin');

        if (! Type::hasType('string_simple_array'))
            Type::addType('string_simple_array', 'Giosh94mhz\GeonamesBundle\Doctrine\Types\StringSimpleArrayType');
    }

    /**
     * Creates a connection to the test database, if there is none yet, and
     * creates the necessary tables.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->useModelSet('giosh94mhz');

        parent::setUp();
    }

    /**
     * Sweeps the database tables and clears the EntityManager.
     */
    protected function tearDown()
    {
        $conn = static::$_sharedConn;

        $this->_sqlLoggerStack->enabled = false;

        if (! $this->_em)
            return;

        foreach ($this->_usedModelSets as $modelSet => $used) {
            if ($used) {
                foreach (array_reverse(static::$_modelSets[$modelSet]) as $className) {
                    $class = $this->_em->getClassMetadata($className);
                    $conn->executeUpdate('DELETE FROM '.$class->getTableName());
                }
            }
        }

        $this->_em->clear();
    }
}
