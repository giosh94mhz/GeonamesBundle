<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Doctrine\Common\Persistence\ObjectManager;
use Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter;
use Giosh94mhz\GeonamesBundle\Exception\MissingToponymException;
use Giosh94mhz\GeonamesBundle\Import\FileReader\ZipReader;
use Giosh94mhz\GeonamesBundle\Entity\HierarchyLink;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 *
 */
class HierarchyImportStepBuilder extends AbstractImportStepBuilder
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $om;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $toponymRepository;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;

        $this->repository = $this->om->getRepository('Giosh94mhzGeonamesBundle:HierarchyLink');

        $this->toponymRepository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Toponym');
    }

    public function getCountryCodes()
    {
        return $this->countryCodes;
    }

    public function setCountryCodes(array $countryCodes)
    {
        $this->countryCodes = $countryCodes;
    }

    public function download(DownloadAdapter $download)
    {
        $this->file = $download->add(self::GEONAME_DUMP_URL . 'hierarchy.zip');
    }

    public function getClass()
    {
        return 'HierarchyLink';
    }

    public function buildReader()
    {
        return new ZipReader($this->file);
    }

    public function buildEntity($value)
    {
        /* @var $parent \Giosh94mhz\GeonamesBundle\Entity\Toponym */
        $parent = $this->toponymRepository->find($value[0]);

        /* @var $child \Giosh94mhz\GeonamesBundle\Entity\Toponym */
        $child = $this->toponymRepository->find($value[1]);

        if (! $parent || ! $child)
            throw new MissingToponymException("HierarchyLink not imported due to missing toponym '$value[0]=>$value[1]'");

        /* @var $link \Giosh94mhz\GeonamesBundle\Entity\HierarchyLink */
        $link = $this->repository->find(array('parent' => $parent->getId(), 'child' => $child->getId())) ?: new HierarchyLink($parent, $child);
        $link->setType($value[2]);

        return $link;
    }
}
