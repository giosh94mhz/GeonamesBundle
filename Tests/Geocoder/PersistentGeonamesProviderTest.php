<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Geocoder;

use Giosh94mhz\GeonamesBundle\Tests\OrmFunctionalTestCase;
use Giosh94mhz\GeonamesBundle\Geocoder\PersistentGeonamesProvider;
use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Geocoder\Geocoder;
use Geocoder\Provider\ProviderInterface;
use Geocoder\Exception\UnsupportedException;
use Geocoder\Exception\NoResultException;

class PersistentGeonamesProviderTest extends OrmFunctionalTestCase
{
    protected $provider;

    public function setUp()
    {
        parent::setUp();
        $this->provider = new PersistentGeonamesProvider($this->_em);
    }

    protected function loadFixtures()
    {
        $fixtures = new BaseFixture();
        $fixtures->load($this->_em);
    }

    public function testSettersAndGetters()
    {
        $this->assertEquals('persistent_geonames', $this->provider->getName());
        // default locale
        $this->assertEquals('en', $this->provider->getLocale());

        // default max result
        $this->assertEquals(Geocoder::MAX_RESULTS, $this->provider->getMaxResults());
        $this->provider->setMaxResults(1);
        $this->assertEquals(1, $this->provider->getMaxResults());

        // default search distances
        $this->assertEquals(array(8, 16, 64), $this->provider->getSearchDistances());
        $this->provider->setSearchDistances(array(1, 2, 3));
        $this->assertEquals(array(1, 2, 3), $this->provider->getSearchDistances());
    }

    public function testReversedData()
    {
        $this->loadFixtures();

        $result = $this->provider->getReversedData(array(45.46427, 9.18951));

        $this->assertNotEmpty($result);

        $this->assertProviderResult(3173435, 'Milano', $result[0]);
    }

    public function testGeocodedData()
    {
        $this->loadFixtures();

        $result = $this->provider->getGeocodedData('Rome');

        $this->assertNotEmpty($result);

        $this->assertProviderResult(3169070, 'Rome', $result[0]);
    }

    /**
     * @expectedException Geocoder\Exception\UnsupportedException
     */
    public function testGeocodingByIpUnsupported()
    {
        $this->provider->getGeocodedData('127.0.0.1');
    }

    public function testGeocodingByIpWithIpProvider()
    {
        $this->loadFixtures();

        $ipProvider = $this->getMockBuilder('Geocoder\Provider\ProviderInterface')->getMock();
        $ipProvider
            ->expects($this->once())
            ->method('getGeocodedData')
            ->will(
                $this->returnValue(array(
                    array('latitude' => 45.46427, 'longitude' => 9.18951)
                ))
            )
        ;

        $this->provider->setIpProvider($ipProvider);

        $this->assertSame($ipProvider, $this->provider->getIpProvider());

        $result = $this->provider->getGeocodedData('127.0.0.1');

        $this->assertNotEmpty($result);

        $this->assertProviderResult(3173435, 'Milano', $result[0]);
    }

    public function testGeocodingByIpWithIpProviderWhichReturnCountryCode()
    {
        $this->loadFixtures();

        // [45.96808,8.97103] is Campione d'Italia but the nearest CH city is Lugano
        $ipProvider = $this->getMockBuilder('Geocoder\Provider\ProviderInterface')->getMock();
        $ipProvider
            ->expects($this->once())
            ->method('getGeocodedData')
            ->will(
                $this->returnValue(array(
                    array('latitude' => 45.96808, 'longitude' => 8.97103, 'countryCode' => 'CH')
                ))
            )
        ;

        $this->provider->setIpProvider($ipProvider);

        $this->assertSame($ipProvider, $this->provider->getIpProvider());

        $result = $this->provider->getGeocodedData('127.0.0.1');

        $this->assertNotEmpty($result);

        $this->assertProviderResult(2659836, 'Lugano', $result[0]);
    }

    public function testGeocodingByIpWithIncrementalDistances()
    {
        $this->loadFixtures();

        // [45.85317,9.39005] is Lecco
        $ipProvider = $this->getMockBuilder('Geocoder\Provider\ProviderInterface')->getMock();
        $ipProvider
            ->expects($this->exactly(2))
            ->method('getGeocodedData')
            ->will(
                $this->returnValue(array(
                    array('latitude' => 45.85317, 'longitude' => 9.39005)
                ))
            )
        ;

        $this->provider->setIpProvider($ipProvider);

        $this->provider->setSearchDistances(array(1, 2, 3, 4, 5, 6, 7));

        try {
            // must fail, since the nearest location is at ~8km
            $this->provider->getGeocodedData('127.0.0.1');
            $this->assertTrue(false);

        } catch (NoResultException $e) {
            $this->assertSame("No toponym found within 7 kilometers", $e->getMessage());
        }

        $this->provider->setSearchDistances(array(2, 4, 6, 8, 10));

        $result = $this->provider->getGeocodedData('127.0.0.1');

        $this->assertNotEmpty($result);

        $this->assertProviderResult(6535698, 'Ello', $result[0]);
    }

    protected function assertProviderResult($expectedId, $expectedToponymName, $actual)
    {
        $this->assertArrayHasKey('toponym', $actual);
        $this->assertArrayHasKey('geonameid', $actual);

        $this->assertEquals($expectedId, $actual['geonameid']);
        $this->assertEquals($expectedToponymName, $actual['toponym']->getName());
    }
}
