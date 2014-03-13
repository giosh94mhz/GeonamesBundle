<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Giosh94mhz\GeonamesBundle\Import\StepBuilder\ContinentImportStepBuilder;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class ContinentImportStepBuilderTest extends AbstractImportStepBuilderTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->step = new ContinentImportStepBuilder($this->_em);
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

        $continent = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Continent')->find(6255146);

        $this->assertInstanceOf('Giosh94mhz\GeonamesBundle\Entity\Continent', $continent);
        $this->assertEquals(6255146, $continent->getId());

        $all = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Continent')->findAll();

        // All continents are imported, even if the Toponym doesn't exists
        $this->assertCount(7, $all);
    }
}
