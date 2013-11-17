<?php
namespace Giosh94mhz\GeonamesBundle\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Entity\Admin1;
use Giosh94mhz\GeonamesBundle\Model\Toponym;
use Doctrine\Common\Persistence\ObjectManager;

/**
 *
 * @author giosh
 *
 */
class Admin1ImportStepBuilder extends AbstractAdminImportStepBuilder
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    public function __construct(ObjectManager $om)
    {
        parent::__construct($om);
        $this->repository = $this->om->getRepository('Giosh94mhzGeonamesBundle:Admin1');
    }

    public function getClass()
    {
        return 'Admin1';
    }

    public function getUrl()
    {
        return self::GEONAME_DUMP_URL . 'admin1CodesASCII.txt';
    }

    protected function buildAdminEntity($value, Toponym $toponym)
    {
        /* @var $admin \Giosh94mhz\GeonamesBundle\Entity\Admin1 */
        $admin = $this->repository->find($toponym) ?: new Admin1($toponym);

        $admin
            ->setCode($value[5])
            ->setCountryCode($value[4])
            ->setName($value[1])
            ->setAsciiName($value[2])
        ;

        return $admin;
    }
}
