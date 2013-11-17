<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Model\Toponym;
use Giosh94mhz\GeonamesBundle\Entity\Admin2;
use Doctrine\Common\Persistence\ObjectManager;

/**
 *
 * @author giosh
 *
 */
class Admin2ImportStepBuilder extends AbstractAdminImportStepBuilder
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    public function __construct(ObjectManager $om)
    {
        parent::__construct($om);
        $this->repository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Admin2');
    }

    public function getClass()
    {
        return 'Admin2';
    }

    public function getUrl()
    {
        return self::GEONAME_DUMP_URL . 'admin2Codes.txt';
    }

    protected function buildAdminEntity($value, Toponym $toponym)
    {
        /* @var $admin \Giosh94mhz\GeonamesBundle\Entity\Admin2 */
        $admin = $this->repository->find($toponym) ?: new Admin2($toponym);

        $admin
            ->setCode($value[6])
            ->setAdmin1Code($value[5])
            ->setCountryCode($value[4])
            ->setName($value[1])
            ->setAsciiName($value[2])
        ;

        return $admin;
    }
}
