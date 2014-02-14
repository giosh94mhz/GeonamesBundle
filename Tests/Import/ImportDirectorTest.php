<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import;

use Giosh94mhz\GeonamesBundle\GeonamesImportEvents;
use Giosh94mhz\GeonamesBundle\Import\ImportDirector;
use Giosh94mhz\GeonamesBundle\Import\StepBuilder\FeatureImportStepBuilder;
use Giosh94mhz\GeonamesBundle\Utils\TxtReader;

class ImportDirectorTest extends \PHPUnit_Framework_TestCase
{
    private $om;
    private $downloader;
    private $dispatcher;

    private $director;

    public function setUp()
    {
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->downloader = $this->getMock('Giosh94mhz\GeonamesBundle\Utils\CurlDownload');

        $this->downloader->expects($this->any())
            ->method('getDirectory')
            ->will($this->returnValue(sys_get_temp_dir() . '/ImportDirectorTest'));

        $this->director = new ImportDirector($this->om, $this->downloader);
        $this->director->setDispatcher($this->dispatcher);

        $this->assertSame($this->dispatcher, $this->director->getDispatcher());
    }

    public function testDownload()
    {
        $this->dispatcher->expects($this->at(0))
            ->method('dispatch')
            ->with(GeonamesImportEvents::PRE_DOWNLOAD)
        ;

        $this->dispatcher->expects($this->at(1))
            ->method('dispatch')
            ->with(GeonamesImportEvents::POST_DOWNLOAD)
        ;

        $step = $this->getMock('Giosh94mhz\GeonamesBundle\Model\Import\ImportStepBuilder');
        $step->expects($this->once())
            ->method('download')
        ;

        $this->director->addStep($step);
        $this->director->download();
    }

    public function testImport()
    {
        $this->dispatcher->expects($this->at(0))
            ->method('dispatch')
            ->with(GeonamesImportEvents::PRE_IMPORT)
        ;

        $this->dispatcher->expects($this->at(1))
            ->method('dispatch')
            ->with(GeonamesImportEvents::PRE_DOWNLOAD)
        ;

        $this->dispatcher->expects($this->at(2))
            ->method('dispatch')
            ->with(GeonamesImportEvents::POST_DOWNLOAD)
        ;

        $this->dispatcher->expects($this->at(3))
            ->method('dispatch')
            ->with(GeonamesImportEvents::PRE_IMPORT_STEP)
        ;

        $this->dispatcher->expects($this->at(4))
            ->method('dispatch')
            ->with(GeonamesImportEvents::ON_IMPORT_STEP_PROGRESS);
        ;

        $this->dispatcher->expects($this->at(5))
            ->method('dispatch')
            ->with(GeonamesImportEvents::POST_IMPORT_STEP)
        ;

        $this->dispatcher->expects($this->at(6))
            ->method('dispatch')
            ->with(GeonamesImportEvents::POST_IMPORT)
        ;

        $step = $this->getMock('Giosh94mhz\GeonamesBundle\Model\Import\ImportStepBuilder');
        $step->expects($this->once())
            ->method('download')
        ;

        $reader = new TxtReader(__DIR__ . '/../Fixtures/fakeImport.txt');
        $step->expects($this->once())
            ->method('buildReader')
            ->will($this->returnValue($reader));

        $this->director->addStep($step);
        $this->director->import();
    }

}
