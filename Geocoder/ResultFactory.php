<?php
namespace Giosh94mhz\GeonamesBundle\Geocoder;

use Geocoder\Result\ResultFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Giosh94mhz\GeonamesBundle\Exception\InvalidArgumentException;

class ResultFactory implements ResultFactoryInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
    }

    /**
     * @param array $data An array of data.
     *
     * @return ResultInterface
     */
    public function createFromArray(array $data)
    {
        if( !isset($data['geonameid']) )
            throw new InvalidArgumentException("A toponym can be loaded only by geonameid");

        return new ResultAdapter($this->loader($data['geonameid']));
    }

    /**
     * @return ResultInterface
    */
    public function newInstance()
    {
        $result=new ResultAdapter();
        $result->setLoaderClosure(function ($geonameid) {
            return $this->loader($geonameid);
        });

        return $result;
    }

    private function loader($geonameid)
    {
        $toponym=$this->em->find('Giosh94mhzGeonamesBundle:Toponym', $geonameid);
        if( !$toponym )
            throw new InvalidArgumentException("Cannot load the Toponym with id '{$geonameid}'");

        return $toponym;
    }
}
