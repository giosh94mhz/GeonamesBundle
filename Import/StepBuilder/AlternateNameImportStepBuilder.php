<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Giosh94mhz\GeonamesBundle\Entity\Toponym;
use Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter;
use Giosh94mhz\GeonamesBundle\Import\FileReader\ZipReader;
use Giosh94mhz\GeonamesBundle\Exception\SkipImportException;
use Giosh94mhz\GeonamesBundle\Entity\AlternateName;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class AlternateNameImportStepBuilder extends AbstractImportStepBuilder
{
    private $om;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $toponymRepository;

    private $file;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        $this->repository = $this->om->getRepository('Giosh94mhzGeonamesBundle:AlternateName');
        $this->toponymRepository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Toponym');
    }

    public function download(DownloadAdapter $download)
    {
        $this->file = $download->add(self::GEONAME_DUMP_URL . 'alternateNames.zip');
    }

    public function getClass()
    {
        return 'AlternateName';
    }

    public function buildReader()
    {
        return new ZipReader($this->file);
    }

    public function buildEntity($value)
    {
        /* @var $toponym \Giosh94mhz\GeonamesBundle\Entity\Toponym */
        $toponym = $this->toponymRepository->find($value[1]);
        if (! $toponym)
            throw new SkipImportException("Toponym doesn't exists");

        /* @var $alternateName \Giosh94mhz\GeonamesBundle\Entity\AlternateName */
        $alternateName = $this->repository->find($value[0]) ?: new AlternateName($value[0]);

        $alternateName
            ->setToponym($toponym)
            ->setLanguage($value[2])
            ->setName($value[3])
            ->setIsPreferredName(! empty($value[4]))
            ->setIsShortName(! empty($value[5]))
            ->setIsColloquial(! empty($value[6]))
            ->setIsHistoric(! empty($value[6]))
        ;

        return $alternateName;
    }
}
