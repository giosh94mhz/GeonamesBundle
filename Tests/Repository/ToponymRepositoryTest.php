<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Repository;

use Giosh94mhz\GeonamesBundle\Tests\OrmFunctionalTestCase;
use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;

class ToponymRepositoryTest extends OrmFunctionalTestCase
{
    private $repository;

    public function repositoryProvider()
    {
        return array(
            array('Giosh94mhzGeonamesBundle:Continent', 48.0, 9.0, 1000, 6255148),
            array('Giosh94mhzGeonamesBundle:Country', 42.8, 12.8, 100, 3175395),
            array('Giosh94mhzGeonamesBundle:Admin1', 45.6, 9.5, 100, 3174618),
            array('Giosh94mhzGeonamesBundle:Admin2', 45.9, 9.2, 50, 3178227),
            array('Giosh94mhzGeonamesBundle:Toponym', 45.4, 9.1, 15, 3173435),
        );
    }

    protected function loadFixtures()
    {
        $fixtures = new BaseFixture();
        $fixtures->load($this->_em);
    }

    /**
     * @dataProvider repositoryProvider
     */
    public function testFindByDistance($entity, $latitude, $longitude, $distanceInKm, $id)
    {
        $this->loadFixtures();

        /* @var $repo \Giosh94mhz\GeonamesBundle\Model\Repository\ToponymRepository */
        $repo = $this->_em->getRepository($entity);

        $results = $repo->findByDistance($latitude, $longitude, $distanceInKm, 1, 0);

        $this->assertNotEmpty($results);

        $toponym = $results[0];

        $this->assertInstanceOf('Giosh94mhz\GeonamesBundle\Model\Toponym', $toponym);

        $this->assertEquals($id, $toponym->getId());
    }
}
