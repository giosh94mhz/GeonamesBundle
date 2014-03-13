<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Giosh94mhz\GeonamesBundle\Import\StepBuilder\HierarchyImportStepBuilder;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class HierarchyImportStepBuilderTest extends AbstractImportStepBuilderTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->step = new HierarchyImportStepBuilder($this->_em);
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

        /* @var $toponym \Giosh94mhz\GeonamesBundle\Model\Toponym */
        $toponym = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Toponym')->find(3174618);

        $this->assertCount(1, $toponym->getParents());
        $this->assertCount(4, $toponym->getChildren());

        $totalParents = 0;
        $totalChildren = 0;
        foreach ($this->_em->getRepository('Giosh94mhzGeonamesBundle:Toponym')->findAll() as $toponym) {
            $totalParents += count($toponym->getParents());
            $totalChildren += count($toponym->getChildren());
        }
        $this->assertEquals($totalParents, $totalChildren);
        $this->assertEquals(8, $totalParents);
    }
}
