<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Model;

use Giosh94mhz\GeonamesBundle\Model\Toponym;
use Giosh94mhz\GeonamesBundle\Model\ToponymProxy;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;

class TestableToponymProxy extends ToponymProxy
{
    public function __construct(Toponym $toponym)
    {
        $this->toponym = $toponym;
    }
}

/**
 * Proxy class to use toponym property as a base class.
 *
 * This trick is required to avoid two problems when importing toponym:
 *   * avoid a discriminator column on toponym (feature is a good
 *     candidate, but not directly supported by Doctrine syntax)
 *   * avoid "downcasting" of previously imported generic toponym
 *     instances
 *
 * @author giosh
 */
class ToponymProxyTest extends \PHPUnit_Framework_TestCase
{
    protected $toponym;

    protected $proxy;

    protected function setUp()
    {
        $this->toponym = $this->getMock('Giosh94mhz\GeonamesBundle\Model\Toponym');
        $this->proxy = new TestableToponymProxy($this->toponym);
    }

    public function testId()
    {
        $this->doTestGetter('getId');
    }

    public function testName()
    {
        $this->doTestGetter('getName');
    }

    public function testAsciiName()
    {
        $this->doTestGetter('getAsciiName');
    }

    public function testAlternateNames()
    {
        $this->doTestGetter('getAlternateNames');
    }

    public function testAlternateNamesArray()
    {
        $this->doTestGetter('getAlternateNamesArray');
    }

    public function testLatitude()
    {
        $this->doTestGetter('getLatitude');
    }

    public function testLongitude()
    {
        $this->doTestGetter('getLongitude');
    }

    public function testFeature()
    {
        $this->doTestGetter('getFeature');
    }

    public function testCountryCode()
    {
        $this->doTestGetter('getCountryCode');
    }

    public function testAlternateCountryCodes()
    {
        $this->doTestGetter('getAlternateCountryCodes');
    }

    public function testAdmin1Code()
    {
        $this->doTestGetter('getAdmin1Code');
    }

    public function testAdmin2Code()
    {
        $this->doTestGetter('getAdmin2Code');
    }

    public function testAdmin3Code()
    {
        $this->doTestGetter('getAdmin3Code');
    }

    public function testAdmin4Code()
    {
        $this->doTestGetter('getAdmin4Code');
    }

    public function testPopulation()
    {
        $this->doTestGetter('getPopulation');
    }

    public function testElevation()
    {
        $this->doTestGetter('getElevation');
    }

    public function testGtopo30()
    {
        $this->doTestGetter('getGtopo30');
    }

    public function testTimezone()
    {
        $this->doTestGetter('getTimezone');
    }

    private function doTestGetter($getter, $return = null)
    {
        $cookie = new \stdClass();
        $this->toponym
            ->expects($this->once())
            ->method($getter)
            ->will($this->returnValue($cookie))
        ;
        $this->assertSame($cookie, call_user_func(array($this->proxy, $getter)));
    }
}
