<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Geocoder;

use Giosh94mhz\GeonamesBundle\Entity\Toponym;
use Giosh94mhz\GeonamesBundle\Entity\Feature;
use Giosh94mhz\GeonamesBundle\Exception\InvalidArgumentException;
use Giosh94mhz\GeonamesBundle\Exception\BadMethodCallException;
use Giosh94mhz\GeonamesBundle\Geocoder\ResultAdapter;

class ResultAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function toponymProvider()
    {
        $all = array(
            array(
                array(6535698, 'Ello', 'Ello', 45.78568, 9.36534, 'P.PPLA3', 'IT', '09', 'LC', '097033', null, 1110, null),
                array(array(45.78568, 9.36534), 45.78568, 9.36534, null, null, null, 'Ello', null, null, null, 'LC', null, '09', null, 'IT', null),
            )
        );

        foreach ($all as &$data) {
            list($class, $code) = explode('.', $data[0][5]);
            $feature = new Feature($class, $code);

            $toponym = new Toponym($data[0][0]);
            $toponym
                ->setName($data[0][1])
                ->setAsciiName($data[0][2])
                ->setLatitude($data[0][3])
                ->setLongitude($data[0][4])
                ->setFeature($feature)
                ->setCountryCode($data[0][6])
                ->setAdmin1Code($data[0][7])
                ->setAdmin2Code($data[0][8])
                ->setAdmin3Code($data[0][9])
                ->setAdmin4Code($data[0][10])
                ->setPopulation($data[0][11])
                ->setElevation($data[0][12])
            ;
            $data[0] = $toponym;
        }

        return $all;
    }

    /**
     * @dataProvider toponymProvider
     */
    public function testAdaptedToponym($toponym, $expected)
    {
        $adapter = new ResultAdapter();
        $adapter->setToponym($toponym);

        $this->assertSame($toponym, $adapter->getToponym());

        $this->assertEquals($expected[0],  $adapter->getCoordinates(),  "Wrong Coordinates");
        $this->assertEquals($expected[1],  $adapter->getLatitude(),     "Wrong Latitude");
        $this->assertEquals($expected[2],  $adapter->getLongitude(),    "Wrong Longitude");
        $this->assertEquals($expected[3],  $adapter->getBounds(),       "Wrong Bounds");
        $this->assertEquals($expected[4],  $adapter->getStreetNumber(), "Wrong StreetNumber");
        $this->assertEquals($expected[5],  $adapter->getStreetName(),   "Wrong StreetName");
        $this->assertEquals($expected[6],  $adapter->getCity(),         "Wrong City");
        $this->assertEquals($expected[7],  $adapter->getZipcode(),      "Wrong Zipcode");
        $this->assertEquals($expected[8],  $adapter->getCityDistrict(), "Wrong CityDistrict");
        $this->assertEquals($expected[9],  $adapter->getCounty(),       "Wrong County");
        $this->assertEquals($expected[10], $adapter->getCountyCode(),   "Wrong CountyCode");
        $this->assertEquals($expected[11], $adapter->getRegion(),       "Wrong Region");
        $this->assertEquals($expected[12], $adapter->getRegionCode(),   "Wrong RegionCode");
        $this->assertEquals($expected[13], $adapter->getCountry(),      "Wrong Country");
        $this->assertEquals($expected[14], $adapter->getCountryCode(),  "Wrong CountryCode");
        $this->assertEquals($expected[15], $adapter->getTimezone(),     "Wrong Timezone");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFromArrayWithoutToponym()
    {
        $adapter = new ResultAdapter();
        $adapter->fromArray();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testFromArrayWithoutLoader()
    {
        $adapter = new ResultAdapter();
        $adapter->fromArray(array(
            'geonameid' => 123
        ));
    }

    public function testFromArray()
    {
        $toponym = $this->toponymProvider();
        $toponym = $toponym[0][0];

        $adapter = new ResultAdapter();
        $adapter->fromArray(array(
            'geonameid' => $toponym->getId(),
            'toponym' => $toponym
        ));

        $this->assertEquals($toponym->getName(), $adapter->getCity());

        $adapter = new ResultAdapter();
        $adapter->setLoaderClosure(function ($id) use ($toponym) {
            return $id == $toponym->getId() ? $toponym : null;
        });
        $adapter->fromArray(array(
            'geonameid' => $toponym->getId(),
        ));

        $this->assertEquals($toponym->getName(), $adapter->getCity());
    }

    public function testToArray()
    {
        $toponym = $this->toponymProvider();
        $toponym = $toponym[0][0];

        $adapter = new ResultAdapter($toponym);
        $array = $adapter->toArray();

        $this->assertEquals($toponym->getId(),           $array['geonameid']);
        $this->assertEquals($adapter->getLatitude(),     $array['latitude']);
        $this->assertEquals($adapter->getLongitude(),    $array['longitude']);
        $this->assertEquals($adapter->getBounds(),       $array['bounds']);
        $this->assertEquals($adapter->getStreetNumber(), $array['streetNumber']);
        $this->assertEquals($adapter->getStreetName(),   $array['streetName']);
        $this->assertEquals($adapter->getZipcode(),      $array['zipcode']);
        $this->assertEquals($adapter->getCity(),         $array['city']);
        $this->assertEquals($adapter->getCityDistrict(), $array['cityDistrict']);
        $this->assertEquals($adapter->getCountry(),      $array['county']);
        $this->assertEquals($adapter->getCountryCode(),  $array['countyCode']);
        $this->assertEquals($adapter->getRegion(),       $array['region']);
        $this->assertEquals($adapter->getRegionCode(),   $array['regionCode']);
        $this->assertEquals($adapter->getCountry(),      $array['country']);
        $this->assertEquals($adapter->getCountryCode(),  $array['countryCode']);
        $this->assertEquals($adapter->getTimezone(),     $array['timezone']);
    }
}
