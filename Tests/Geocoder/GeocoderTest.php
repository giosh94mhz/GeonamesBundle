<?php

namespace Giosh94mhz\GeonamesBundle\Tests\Geocoder;

use Geocoder\Geocoder;
use Giosh94mhz\GeonamesBundle\Tests\OrmFunctionalTestCase;
use Giosh94mhz\GeonamesBundle\Geocoder\PersistentGeonamesProvider;
use Giosh94mhz\GeonamesBundle\Geocoder\ResultFactory;
use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Giosh94mhz\GeonamesBundle\Geocoder\MultipleResultFactory;
use Giosh94mhz\GeonamesBundle\Model\Toponym;

class GeocoderTest extends OrmFunctionalTestCase
{
    protected $geocoder;

    protected function setUp()
    {
        parent::setUp();

        $this->geocoder = new Geocoder(
            new PersistentGeonamesProvider($this->_em),
            new ResultFactory($this->_em)
        );
    }

    protected function loadFixtures()
    {
        $fixtures = new BaseFixture();
        $fixtures->load($this->_em);
    }

    public function testGeocode()
    {
        $this->loadFixtures();

        $result = $this->geocoder->geocode('Milano');

        $this->assertInstanceOf('Geocoder\Result\ResultInterface', $result);
        $this->assertInstanceOf('Giosh94mhz\GeonamesBundle\Geocoder\ResultAdapter', $result);

        $this->assertEquals(
            $this->_em->find('Giosh94mhzGeonamesBundle:Toponym', 3173435)->getId(),
            $result->getToponym()->getId()
        );
    }

    public function testMultipleGeocode()
    {
        $this->loadFixtures();

        $this->geocoder->setResultFactory(new MultipleResultFactory($this->_em));

        $result = $this->getFirstResultFromStorage(
            $this->geocoder->geocode('Rom'), 2
        );

        return $result->getToponym();
    }

    /**
     * @depends testMultipleGeocode
     */
    public function testMultipleGeocodeWithLimit(Toponym $expected)
    {
        $this->loadFixtures();

        $this->geocoder->setResultFactory(new MultipleResultFactory($this->_em));

        $this->geocoder->limit(1);

        $this->assertEquals($this->geocoder->getMaxResults(), 1);

        $result = $this->getFirstResultFromStorage(
            $this->geocoder->geocode('Rom'), 1
        );

        $this->assertEquals($expected->getId(), $result->getToponym()->getId());
    }

    /**
     * @return \Giosh94mhz\GeonamesBundle\Geocoder\ResultAdapter
     */
    protected function getFirstResultFromStorage($objects, $expectedCount)
    {
        $this->assertInstanceOf('\SplObjectStorage', $objects);
        $this->assertCount($expectedCount, $objects);

        $objects->rewind();
        $result = $objects->current();

        $this->assertInstanceOf('Geocoder\Result\ResultInterface', $result);
        $this->assertInstanceOf('Giosh94mhz\GeonamesBundle\Geocoder\ResultAdapter', $result);

        return $result;
    }
}

