<?php
namespace Giosh94mhz\GeonamesBundle\Import;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Giosh94mhz\GeonamesBundle\Model\Import\ImportStepBuilder;
use Giosh94mhz\GeonamesBundle\GeonamesImportEvents;
use Giosh94mhz\GeonamesBundle\Event\ImportEvent;
use Giosh94mhz\GeonamesBundle\Utils\CurlDownload;
use Giosh94mhz\GeonamesBundle\Event\OnProgressEvent;
use Giosh94mhz\GeonamesBundle\Exception\SkipImportException;
use Giosh94mhz\GeonamesBundle\Event\ImportErrorEvent;
use Giosh94mhz\GeonamesBundle\Event\PostImportEvent;
use Giosh94mhz\GeonamesBundle\Model\Import\ImportDirector as ImportDirectorInterface;
use Giosh94mhz\GeonamesBundle\Exception\FailedImportException;

class ImportDirector implements ImportDirectorInterface
{
    protected $om;

    protected $downloadDirectory;

    protected $dispatcher;

    protected $builders;

    public function __construct(ObjectManager $om, $downloadDirectory)
    {
        $this->om = $om;
        $this->downloadDirectory = $downloadDirectory;
        $this->builders = array();
    }

    public function addStep(ImportStepBuilder $builder)
    {
        $this->builders[] = $builder;

        return $this;
    }

    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    public function import()
    {
        if ($this->dispatcher)
            $this->dispatcher->dispatch(GeonamesImportEvents::PRE_IMPORT, new ImportEvent());

        $this->download();

        $synced = $skipped = $errors = 0;

        /* @var $builder \Giosh94mhz\GeonamesBundle\Model\Import\ImportStepBuilder */
        foreach ($this->builders as $builder)
            $this->importStep($builder, $synced, $skipped, $errors);

        if ($this->dispatcher) {
            $this->dispatcher->dispatch(GeonamesImportEvents::POST_IMPORT,
                                        new PostImportEvent($synced, $skipped, $errors));
        }
    }

    private function importStep(ImportStepBuilder $builder, &$totSynced, &$totSkipped, &$totErrors)
    {
        if ($this->dispatcher)
            $this->dispatcher->dispatch(GeonamesImportEvents::PRE_IMPORT_STEP, new ImportEvent());

        $synced = $skipped = $errors = 0;

        $reader = $builder->buildReader();
        $reader->open();

        $totalSize = $reader->count();
        foreach ($reader as $size => $value) {
            try {
                $this->emptyToNull($value);

                $entity = $builder->buildEntity($value);

                $this->om->persist($entity);

                ++ $synced;

            } catch (SkipImportException $e) {
                ++ $skipped;
                if ($this->dispatcher) {
                    $ev = new ImportErrorEvent($builder->getClass(), $value, $e);
                    $this->dispatcher->dispatch(GeonamesImportEvents::ON_SKIP, $ev);
                }

            } catch (FailedImportException $e) {
                ++ $errors;
                if ($this->dispatcher) {
                    $ev = new ImportErrorEvent($builder->getClass(), $value, $e);
                    $this->dispatcher->dispatch(GeonamesImportEvents::ON_ERROR, $ev);
                }
            }
            if ($this->dispatcher)
                $this->dispatcher->dispatch(GeonamesImportEvents::ON_IMPORT_STEP_PROGRESS,
                                            new OnProgressEvent($totalSize, $size + 1));
        }

        $this->om->flush();

        $builder->finalize();

        if ($this->dispatcher) {
            $this->dispatcher->dispatch(GeonamesImportEvents::POST_IMPORT_STEP,
                                        new PostImportEvent($synced, $skipped, $errors));
        }

        $totSynced += $synced;
        $totSkipped += $skipped;
        $totErrors += $errors;
    }

    public function download()
    {
        if ($this->dispatcher)
            $this->dispatcher->dispatch(GeonamesImportEvents::PRE_DOWNLOAD, new ImportEvent());

        $fs = new Filesystem();
        $fs->mkdir($this->downloadDirectory);

        $downloader = new CurlDownload();
        $downloader->setDirectory($this->downloadDirectory);

        /* @var $builder \Giosh94mhz\GeonamesBundle\Model\Import\ImportStepBuilder */
        foreach ($this->builders as $builder)
            $builder->download($downloader);

        if ($this->dispatcher) {
            $downloader->setProgressFunction(function ($total, $current) {
                $this->dispatcher->dispatch(GeonamesImportEvents::ON_DOWNLOAD_PROGRESS, new OnProgressEvent($total, $current));
            });
        }

        $result = $downloader->download();

        if ($this->dispatcher)
            $this->dispatcher->dispatch(GeonamesImportEvents::POST_DOWNLOAD, new ImportEvent());

        return $result;
    }

    private function emptyToNull(array &$values)
    {
        foreach ($values as &$value)
        if ($value === '')
            $value = null;
    }
}
