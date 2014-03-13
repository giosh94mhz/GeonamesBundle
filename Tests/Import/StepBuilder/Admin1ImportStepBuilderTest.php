<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Giosh94mhz\GeonamesBundle\Import\StepBuilder\Admin1ImportStepBuilder;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class Admin1ImportStepBuilderTest extends AbstractImportStepBuilderTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->step = new Admin1ImportStepBuilder($this->_em);
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

        /* @var $admin \Giosh94mhz\GeonamesBundle\Model\Admin1 */
        $admin = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Admin1')->find(3165361);

        $this->assertInstanceOf('Giosh94mhz\GeonamesBundle\Entity\Admin1', $admin);
        $this->assertEquals(3165361, $admin->getId());
        $this->assertEquals('IT', $admin->getCountryCode());
        $this->assertEquals('16', $admin->getAdmin1Code());
        $this->assertEquals('Tuscany', $admin->getName());
        $this->assertEquals('Tuscany', $admin->getAsciiName());

        $this->assertCount(2, $this->_em->getRepository('Giosh94mhzGeonamesBundle:Admin1')->findAll());
    }
}
