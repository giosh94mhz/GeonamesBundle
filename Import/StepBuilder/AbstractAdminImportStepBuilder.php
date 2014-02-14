<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Doctrine\Common\Persistence\ObjectManager;
use Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter;
use Giosh94mhz\GeonamesBundle\Import\FileReader\TxtReader;
use Giosh94mhz\GeonamesBundle\Entity\Country;
use Giosh94mhz\GeonamesBundle\Model\Toponym;
use Giosh94mhz\GeonamesBundle\Exception\MissingToponymException;
use Giosh94mhz\GeonamesBundle\Exception\SkipImportException;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
abstract class AbstractAdminImportStepBuilder extends AbstractImportStepBuilder
{
    protected $om;
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $toponymRepository;

    private $file;
    private $countryCodes;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;

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
        $this->file = $download->add($this->getUrl());
    }

    public function buildReader()
    {
        return new TxtReader($this->file);
    }

    public function buildEntity($value)
    {
        if (empty($value[3]))
            throw new SkipImportException("Admin level '{$value[0]}' not imported because is no longer bound to a toponym");

        /* @var $toponym \Giosh94mhz\GeonamesBundle\Entity\Toponym */
        if (! ($toponym = $this->toponymRepository->find($value[3])))
            throw new MissingToponymException("Skipped '{$value[0]}' due to missing toponym '{$value[3]}'");

        $codes = explode('.', $value[0]);

        if (!empty($this->countryCodes) && !in_array($codes[0], $this->countryCodes))
            throw new SkipImportException("Skipped admin level '{$value[0]}' because country '{$codes[0]}' is not enabled");

        $value = array_merge($value, $codes);

        $admin = $this->buildAdminEntity($value, $toponym);

        return $admin;
    }

    abstract protected function buildAdminEntity($value, Toponym $toponym);

}
