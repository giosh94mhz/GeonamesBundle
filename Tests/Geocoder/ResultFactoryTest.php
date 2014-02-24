<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Geocoder;

use Giosh94mhz\GeonamesBundle\Exception\InvalidArgumentException;
use Giosh94mhz\GeonamesBundle\Model\Toponym;
use Giosh94mhz\GeonamesBundle\Geocoder\ResultFactory;
use Giosh94mhz\GeonamesBundle\Tests\OrmFunctionalTestCase;
use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Giosh94mhz\GeonamesBundle\Geocoder\ResultAdapter;

class ResultFactoryTest extends OrmFunctionalTestCase
{
    protected $factory;

    public function setUp()
    {
        parent::setUp();
        $this->factory = new ResultFactory($this->_em);
    }

    protected function loadFixtures()
    {
        $fixtures = new BaseFixture();
        $fixtures->load($this->_em);
    }

    public function testNewInstance()
    {
        $instance = $this->factory->newInstance();

        $this->assertInstanceOf('Geocoder\Result\ResultInterface', $instance);
    }

    public function testCreateFromArray()
    {
        $this->loadFixtures();

        $instance = $this->factory->createFromArray(array(
            'geonameid' => 3169070
        ));

        $this->assertInstanceOf('Geocoder\Result\ResultInterface', $instance);

        $this->assertEquals('Rome', $instance->getCity());
    }
}
