<?php
namespace Giosh94mhz\GeonamesBundle\Geocoder;

use Doctrine\Common\Persistence\ObjectManager;
use Geocoder\Result\ResultFactoryInterface;
use Giosh94mhz\GeonamesBundle\Exception\InvalidArgumentException;

class ResultFactory implements ResultFactoryInterface
{
    protected $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @param array $data An array of data.
     *
     * @return ResultInterface
     */
    public function createFromArray(array $data)
    {
        if (! isset($data['geonameid']) )
            throw new InvalidArgumentException("A toponym can be loaded only by geonameid");

        return new ResultAdapter($this->loader($data['geonameid']));
    }

    /**
     * @return ResultInterface
    */
    public function newInstance()
    {
        $result = new ResultAdapter();
        $result->setLoaderClosure(function ($geonameid) {
            return $this->loader($geonameid);
        });

        return $result;
    }

    private function loader($geonameid)
    {
        $toponym = $this->om->find('Giosh94mhzGeonamesBundle:Toponym', $geonameid);
        if (! $toponym )
            throw new InvalidArgumentException("Cannot load the Toponym with id '{$geonameid}'");

        return $toponym;
    }
}
