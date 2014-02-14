<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Doctrine\Common\Persistence\ObjectManager;
use Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter;
use Giosh94mhz\GeonamesBundle\Import\FileReader\TxtReader;
use Giosh94mhz\GeonamesBundle\Entity\Country;
use Giosh94mhz\GeonamesBundle\Exception\MissingToponymException;
use Giosh94mhz\GeonamesBundle\Exception\SkipImportException;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 *
 */
class CountryImportStepBuilder extends AbstractImportStepBuilder
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

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;

        $this->repository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Country');

        $this->toponymRepository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Toponym');

        $this->countryCodes = array();
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
        $this->file = $download->add(self::GEONAME_DUMP_URL . 'countryInfo.txt');
    }

    public function getClass()
    {
        return 'Country';
    }

    public function buildReader()
    {
        return new TxtReader($this->file);
    }

    public function buildEntity($value)
    {
        if (empty($value[16]))
            throw new SkipImportException("Country '{$value[0]}' not imported because is no longer bound to a toponym");

        /* @var $toponym \Giosh94mhz\GeonamesBundle\Entity\Toponym */
        $toponym = $this->toponymRepository->find($value[16]);
        if (! $toponym)
            throw new MissingToponymException("Country '{$value[0]}' not imported due to missing toponym '{$value[16]}'");

        /* @var $country \Giosh94mhz\GeonamesBundle\Entity\Country */
        $country = $this->repository->find($toponym) ?: new Country($toponym);

        $languages = empty($value[15])? array() : explode(',', $value[15]);
        $neighbours = empty($value[17])? array() : explode(',', $value[17]);

        $country
            ->setIso($value[0])
            ->setIso3($value[1])
            ->setIsoNumeric($value[2])
            ->setFipsCode($value[3])
            ->setName($value[4])
            ->setCapital($value[5])
            ->setArea($value[6])
            ->setPopulation($value[7])
            ->setContinent($value[8])
            ->setTopLevelDomain($value[9])
            ->setCurrency($value[10])
            ->setCurrencyName($value[11])
            ->setPhone($value[12])
            ->setPostalCodeFormat($value[13])
            ->setPostalCodeRegex($value[14])
            ->setLanguages($languages)
            ->setNeighbours($neighbours)
            ->setEquivalentFipsCode($value[18])
        ;

        return $country;
    }
}
