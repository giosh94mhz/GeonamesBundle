<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Import\StepBuilder\FeatureImportStepBuilder;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class LanguageImportStepBuilderTest extends AbstractImportStepBuilderTest
{
    private $step;

    protected function setUp()
    {
        parent::setUp();
        $this->step = new FeatureImportStepBuilder($this->_em);
        $this->director->addStep($this->step);
    }

    public function testLocale()
    {
        $this->assertEquals('en', $this->step->getLocale());

        $this->assertSame($this->step, $this->step->setLocale('it'));

        $this->assertEquals('it', $this->step->getLocale());
    }

    public function testFullImport()
    {
        $this->director->import();

        $all = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Feature')->findAll();

        $this->assertGreaterThan(0, $all);
    }
}
