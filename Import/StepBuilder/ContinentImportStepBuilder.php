<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Doctrine\Common\Persistence\ObjectManager;
use Giosh94mhz\GeonamesBundle\Entity\Continent;
use Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter;
use Giosh94mhz\GeonamesBundle\Entity\Toponym;
use Giosh94mhz\GeonamesBundle\Import\ContinentReader;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class ContinentImportStepBuilder extends AbstractImportStepBuilder
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

    private $continentFeature;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        $this->repository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Continent');
        $this->toponymRepository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Toponym');
        $this->feature = $this->om->find('Giosh94mhzGeonamesBundle:Feature', array('class' => 'L', 'code' => 'CONT'));
    }

    public function download(DownloadAdapter $download)
    {
        $this->file = $download->add(self::GEONAME_DUMP_URL . 'readme.txt');
    }

    public function getClass()
    {
        return 'Continent';
    }

    public function buildReader()
    {
        return new ContinentReader($this->file);
    }

    public function buildEntity($value)
    {
        /* @var $continent \Giosh94mhz\GeonamesBundle\Entity\Continent */
        $continent = $this->repository->find($value[2]);

        if (! $continent) {
            /* @var $toponym \Giosh94mhz\GeonamesBundle\Entity\Toponym */
            $toponym = $this->toponymRepository->find($value[2]) ?: $this->createFallbackToponym($value[0], $value[1], $value[2]);

            $continent = new Continent($toponym);
        }

        $continent
            ->setCode($value[0])
            ->setName($value[1]);

        return $continent;
    }

    private function createFallbackToponym($code, $name, $toponymId)
    {
        $toponym = new Toponym($toponymId);
        $toponym
            ->setName($name)
            ->setAsciiName($name)
            ->setFeature($this->feature)
            ->setLastModify(new \DateTime('1970-01-01'));

        $this->om->persist($toponym);

        return $toponym;
    }
}
