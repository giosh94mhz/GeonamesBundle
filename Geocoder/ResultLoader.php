<?php
namespace Giosh94mhz\GeonamesBundle\Geocoder;

use Giosh94mhz\GeonamesBundle\Exception\InvalidArgumentException;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Allocate and load (delayed) an instance. Used to reduce code duplication.
 *
 * @author Giorgio Premi <giosh94mhz@agmail.com>
 * @internal
 */
class ResultLoader
{
    protected $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @return ResultAdapter
     */
    public function newInstance()
    {
        $result = new ResultAdapter();
        $callback = array($this, 'createFromId');
        $result->setLoaderClosure(function ($geonameid) use ($callback) {
            return call_user_func($callback, $geonameid);
        });

        return $result;
    }

    /**
     * @param integer $geonameid A geonameid to load.
     *
     * @return Toponym
     */
    public function createFromId($geonameid)
    {
        $toponym = $this->om->find('Giosh94mhzGeonamesBundle:Toponym', $geonameid);
        if (! $toponym )
            throw new InvalidArgumentException("Cannot load the Toponym with id '{$geonameid}'");

        return $toponym;
    }
}
