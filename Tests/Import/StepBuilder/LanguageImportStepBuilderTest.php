<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Import\StepBuilder\LanguageImportStepBuilder;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class LanguageImportStepBuilderTest extends AbstractImportStepBuilderTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->step = new LanguageImportStepBuilder($this->_em);
        $this->director->addStep($this->step);
    }

    public function testFullImport()
    {
        $this->doDirectorImport();

        $this->assertCount(8, $this->_em->getRepository('Giosh94mhzGeonamesBundle:Language')->findAll());
    }
}
