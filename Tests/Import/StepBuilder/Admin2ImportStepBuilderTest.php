<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Giosh94mhz\GeonamesBundle\Import\StepBuilder\Admin2ImportStepBuilder;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class Admin2ImportStepBuilderTest extends AbstractImportStepBuilderTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->step = new Admin2ImportStepBuilder($this->_em);
        $this->director->addStep($this->step);
    }

    protected function loadFixtures()
    {
        $fixtures = new BaseFixture();
        $fixtures->load($this->_em);
    }

    public function testFullImport()
    {
        $this->doDirectorImport();

        /* @var $admin \Giosh94mhz\GeonamesBundle\Model\Admin2 */
        $admin = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Admin2')->find(3176958);

        $this->assertInstanceOf('Giosh94mhz\GeonamesBundle\Entity\Admin2', $admin);
        $this->assertEquals(3176958, $admin->getId());
        $this->assertEquals('IT', $admin->getCountryCode());
        $this->assertEquals('16', $admin->getAdmin1Code());
        $this->assertEquals('FI', $admin->getAdmin2Code());
        $this->assertEquals('Provincia di Firenze', $admin->getName());
        $this->assertEquals('Provincia di Firenze', $admin->getAsciiName());

        $this->assertCount(3, $this->_em->getRepository('Giosh94mhzGeonamesBundle:Admin2')->findAll());
    }
}
