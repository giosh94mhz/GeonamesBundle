<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Giosh94mhz\GeonamesBundle\Entity\Toponym;
use Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter;
use Giosh94mhz\GeonamesBundle\Import\FileReader\ZipReader;
use Giosh94mhz\GeonamesBundle\Exception\InvalidFeature;
use Giosh94mhz\GeonamesBundle\Import\FileReader\ChainedReader;
use Giosh94mhz\GeonamesBundle\Exception\SkipImportException;
use Giosh94mhz\GeonamesBundle\Import\FeatureMatch;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class ToponymImportStepBuilder extends AbstractImportStepBuilder
{
    const CITY_SMALL   =  1000;
    const CITY_MEDIUM  =  5000;
    const CITY_BIG     = 15000;

    private $om;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $featureRepo;

    private $featureMatch;

    private $countryCodes;
    private $cityPopulation;

    private $alternateNamesIncluded;
    private $alternateCountryCodesIncluded;

    private $files;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        $this->repository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Toponym');
        $this->featureRepo = $this->om->getRepository('Giosh94mhzGeonamesBundle:Feature');

        $this->countryCodes = array();
        $this->cityPopulation = null;

        $this->alternateNamesIncluded = true;
        $this->alternateCountryCodesIncluded = true;

        $this->files = array();
    }

    public function getFeatureMatch()
    {
        return $this->featureMatch;
    }

    public function setFeatureMatch(FeatureMatch $featureMatch)
    {
        $this->featureMatch = $featureMatch;

        return $this;
    }

    public function getCityPopulation()
    {
        return $this->cityPopulation;
    }

    public function setCityPopulation($cityPopulation)
    {
        $this->cityPopulation = $cityPopulation;

        return $this;
    }

    public function getCountryCodes()
    {
        return $this->countryCodes;
    }

    public function setCountryCodes(array $countryCodes)
    {
        $this->countryCodes = $countryCodes;
    }

    public function isAlternateNamesIncluded()
    {
        return $this->alternateNamesIncluded;
    }

    public function setAlternateNamesIncluded($alternateNamesIncluded)
    {
        $this->alternateNamesIncluded = (boolean) $alternateNamesIncluded;

        return $this;
    }

    public function isAlternateCountryCodesIncluded()
    {
        return $this->alternateCountryCodesIncluded;
    }

    public function setAlternateCountryCodesIncluded($alternateCountryCodesIncluded)
    {
        $this->alternateCountryCodesIncluded = (boolean) $alternateCountryCodesIncluded;

        return $this;
    }

    public function download(DownloadAdapter $download)
    {
        $this->files = array();
        foreach ($this->getSourcesList() as $url)
            $this->files[] = $download->add($url);
    }

    private function getSourcesList()
    {
        $baseUrl = self::GEONAME_DUMP_URL;
        $sources = array();

        $forcedInclude = $this->featureMatch->getForceInclude();
        if (! empty($forcedInclude)
            || (empty($this->countryCodes) && !$this->cityPopulation)
            || $this->cityPopulation && $this->cityPopulation < self::CITY_SMALL
        ) {
            $sources[] = $baseUrl . 'allCountries.zip';

        } else {
            if ($this->cityPopulation >= self::CITY_BIG) {
                $sources[] =  $baseUrl . 'cities' . self::CITY_BIG . '.zip';

            } elseif ($this->cityPopulation >= self::CITY_MEDIUM) {
                $sources[] =  $baseUrl . 'cities' . self::CITY_MEDIUM . '.zip';

            } elseif ($this->cityPopulation >= self::CITY_SMALL) {
                $sources[] =  $baseUrl . 'cities' . self::CITY_SMALL . '.zip';
            }

            foreach ($this->countryCodes as $countryCode)
                $sources[] = $baseUrl . $countryCode . '.zip';
        }

        return $sources;
    }

    public function getClass()
    {
        return 'Toponym';
    }

    public function buildReader()
    {
        $reader = new ChainedReader();
        foreach ($this->files as $file)
            $reader->append(new ZipReader($file));

        return $reader;
    }

    public function buildEntity($value)
    {
        $feature = $this->checkPrecondition($value);

        /* @var $toponym \Giosh94mhz\GeonamesBundle\Entity\Toponym */
        $toponym = $this->repository->find($value[0]) ?: new Toponym($value[0]);

        $toponym
            ->setName($value[1])
            ->setAsciiName($value[2])
            ->setLatitude($value[4])
            ->setLongitude($value[5])
            ->setFeature($feature)
            ->setCountryCode($value[8])
            ->setAdmin1Code($value[10])
            ->setAdmin2Code($value[11])
            ->setAdmin3Code($value[12])
            ->setAdmin4Code($value[13])
            ->setPopulation($value[14])
            ->setElevation($value[15])
            ->setGtopo30($value[16]); // better name dem?

        if ($this->isAlternateNamesIncluded()) {
            $altNames = empty($value[3])? array() : explode(',', $value[3]);
            $toponym->setSimpleAlternateNames($altNames);
        }

        if ($this->isAlternateCountryCodesIncluded()) {
            $altCountryCode = empty($value[9])? array() : explode(',', $value[9]);
            $toponym->setAlternateCountryCodes($altCountryCode);
        }

        if (!empty($value[17]))
            $toponym->setTimezone(new \DateTimeZone($value[17]));

        if (!empty($value[18]))
            $toponym->setLastModify(new \DateTime($value[18]));

        return $toponym;
    }

    private function checkPrecondition(array $value)
    {
        if (empty($value[6]) || empty($value[7]))
            throw new InvalidFeature("Invalid feature class='{$value[6]}' code='$value[7]'");

        if ($this->featureMatch && ! $this->featureMatch->isIncluded($value[6], $value[7]) )
            throw new SkipImportException("Feature should not be included");

        if (!empty($this->countryCodes) || !empty($this->cityPopulation)) {
            if( !in_array($value[8], $this->countryCodes) && $value[14] < $this->cityPopulation )
                throw new SkipImportException("Toponym does not match country or cities limits");
        }

        return $this->featureRepo->find(array(
            'class' => $value[6],
            'code' => $value[7]
        ));
    }
}
