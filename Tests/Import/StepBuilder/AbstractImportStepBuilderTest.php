<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Import\ImportDirector;
use Giosh94mhz\GeonamesBundle\Import\StepBuilder\AbstractImportStepBuilder;
use Giosh94mhz\GeonamesBundle\Tests\OrmFunctionalTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
abstract class AbstractImportStepBuilderTest extends OrmFunctionalTestCase
{
    protected $downloaderMock;

    protected $director;

    protected $step;

    protected function setUp()
    {
        parent::setUp();

        $this->downloaderMock = $this->getMock('Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter');
        $this->downloaderMock
            ->expects($this->any())
            ->method('add')
            ->will($this->returnCallback(function ($url) {
                return str_replace(
                    AbstractImportStepBuilder::GEONAME_DUMP_URL,
                    __DIR__.'/../../Fixtures/',
                    $url
                );
            }))
        ;

        $this->downloaderMock
            ->expects($this->any())
            ->method('getDirectory')
            ->will($this->returnValue(sys_get_temp_dir()))
        ;

        $this->director = new ImportDirector($this->_em, $this->downloaderMock);
        $this->director->setDispatcher(new EventDispatcher());
    }

    public function testClass()
    {
        if (! $this->step) {
            $this->markTestSkipped("Step is not initialized");

            return;
        }

        $this->assertNotEmpty($this->step->getClass());
    }

    abstract public function testFullImport();

    protected function loadFixtures()
    {}

    protected function doDirectorImport()
    {
        $this->loadFixtures();

        $this->director->import();

        $this->_em->clear();
    }
}
