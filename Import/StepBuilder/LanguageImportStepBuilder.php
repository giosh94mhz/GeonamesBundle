<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Doctrine\Common\Persistence\ObjectManager;
use Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter;
use Giosh94mhz\GeonamesBundle\Import\FileReader\TxtReader;
use Giosh94mhz\GeonamesBundle\Entity\Language;
use Giosh94mhz\GeonamesBundle\Exception\SkipImportException;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class LanguageImportStepBuilder extends AbstractImportStepBuilder
{
    private $om;

    private $file;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        $this->repository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Language');
    }

    public function download(DownloadAdapter $download)
    {
        $this->file = $download->add(self::GEONAME_DUMP_URL .  "iso-languagecodes.txt");
    }

    public function getClass()
    {
        return 'Language';
    }

    public function buildReader()
    {
        return new TxtReader($this->file);
    }

    public function buildEntity($value)
    {
        if ($value[0] === 'ISO 639-3')
            throw new SkipImportException("Language file header is not commented out");

        /* @var $language \Giosh94mhz\GeonamesBundle\Entity\Language */
        $language = $this->repository->find($value[0]) ?: new Language($value[0]);

        $language
            ->setIso639p2($value[1])
            ->setIso639p1($value[2])
            ->setName($value[3]);

        return $language;
    }
}
