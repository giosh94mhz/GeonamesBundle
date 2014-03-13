<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Giosh94mhz\GeonamesBundle\Import\StepBuilder\CountryImportStepBuilder;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class CountryImportStepBuilderTest extends AbstractImportStepBuilderTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->step = new CountryImportStepBuilder($this->_em);
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

        $country = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Country')->find(2635167);

        $this->assertInstanceOf('Giosh94mhz\GeonamesBundle\Entity\Country', $country);
        $this->assertEquals(2635167, $country->getId());

        $all = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Country')->findAll();

        $this->assertCount(3, $all);
    }
}
