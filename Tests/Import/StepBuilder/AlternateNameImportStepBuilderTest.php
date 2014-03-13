<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Giosh94mhz\GeonamesBundle\Import\StepBuilder\AlternateNameImportStepBuilder;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class AlternateNameImportStepBuilderTest extends AbstractImportStepBuilderTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->step = new AlternateNameImportStepBuilder($this->_em);
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
        $toponym = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Toponym')->find(2635167);

        $this->assertCount(9, $toponym->getAlternateNames());
        $this->assertCount(9, $toponym->getAlternateNamesArray());

        $altNames = $toponym->getAlternateNames();

        /* @var $name \Giosh94mhz\GeonamesBundle\Model\AlternateName */
        foreach ($altNames as $name) {
            if ($name->isPreferredName() && $name->getLanguage() == 'en')
                $this->assertEquals('United Kingdom', $name->getName());
        }

        $this->assertCount(80, $this->_em->getRepository('Giosh94mhzGeonamesBundle:AlternateName')->findAll());
    }
}
