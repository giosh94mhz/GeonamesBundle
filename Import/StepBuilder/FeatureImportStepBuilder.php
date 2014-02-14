<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Doctrine\Common\Persistence\ObjectManager;
use Giosh94mhz\GeonamesBundle\Entity\Feature;
use Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter;
use Giosh94mhz\GeonamesBundle\Import\FileReader\TxtReader;
use Giosh94mhz\GeonamesBundle\Import\FeatureCollection;
use Giosh94mhz\GeonamesBundle\Exception\SkipImportException;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class FeatureImportStepBuilder extends AbstractImportStepBuilder
{
    private $om;

    private $locale;
    private $features;
    private $forcedFeatures;

    private $file;
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    public function __construct(ObjectManager $om, FeatureCollection $features, FeatureCollection $forcedFeatures)
    {
        $this->om = $om;
        $this->repository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Feature');

        $this->locale = 'en';
        $this->features = $features;
        $this->forcedFeatures = $forcedFeatures;
    }

    /**
     * Get the locale used for feature name and description
     *
     * @return string locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set the locale used for feature name and description
     *
     * @param string $locale Locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale ?: 'en';

        return $this;
    }

    public function download(DownloadAdapter $download)
    {
        $this->file = $download->add(self::GEONAME_DUMP_URL .  "featureCodes_{$this->locale}.txt");
    }

    public function getClass()
    {
        return 'Feature';
    }

    public function buildReader()
    {
        return new TxtReader($this->file);
    }

    public function buildEntity($value)
    {
        if ( mb_strtolower($value[0]) === 'null' )
            throw new SkipImportException("Well-known null feature");

        list($class, $code) = explode('.', $value[0], 2);

        /* @var $feature \Giosh94mhz\GeonamesBundle\Entity\Feature */
        $feature=$this->repository->find(array( 'code' => $code, 'class' => $class )) ?: new Feature($class, $code);

        $feature
            ->setName($value[1])
            ->setDescription($value[2]);

        return $feature;
    }

    public function finalize()
    {
        $this->features->refresh();
        $this->forcedFeatures->refresh();
    }
}
