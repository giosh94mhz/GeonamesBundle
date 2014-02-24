<?php
namespace Giosh94mhz\GeonamesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Giosh94mhz\GeonamesBundle\Model\Repository\ToponymRepository as ToponymRepositoryInterface;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\Query\Expr\Select;

class ToponymRepository extends EntityRepository implements ToponymRepositoryInterface
{
    public function findByDistance($latitude, $longitude, $distanceInKm, $limit = null, $offset = null)
    {
        /*
        // this cannot be used since there is no way to express "HIDDEN distance"
        $hiddenDistance = new Func('GEO_DISTANCE', array(
            $latitude,
            $longitude,
            'toponym.latitude',
            'toponym.longitude'
        ));
        /*/
        $hiddenDistance = sprintf(
            'GEO_DISTANCE(%F, %F, toponym.latitude, toponym.longitude) as HIDDEN distance',
            $latitude,
            $longitude
        );
        //*/

        /*
        // these cannot be used since Doctrine do not allow Func as
        // a conditional expression, and using "Func <> 0" prevent
        // MySQL (a maybe other) optimizer to use lat/lon index
        $latitudeWithin = new Func('LATITUDE_WITHIN', array(
            'toponym.latitude',
            $latitude,
            $distanceInKm
        ));

        $longitudeWithin = new Func('LONGITUDE_WITHIN', array(
            'toponym.longitude',
            'toponym.latitude',
            $longitude,
            $distanceInKm

        ));
        /*/
        $latRange = $distanceInKm / 111.;
        $latitudeWithin = sprintf(
            'toponym.latitude BETWEEN %F AND %F',
            $latitude - $latRange,
            $latitude + $latRange
        );

        $lonRange = $distanceInKm / abs(cos($latitude) * 111.);
        $longitudeWithin = sprintf(
            'toponym.longitude BETWEEN %F AND %F',
            $longitude - $lonRange,
            $longitude + $lonRange
        );
        //*/

        $qb = $this->createToponymQueryBuilder(array($hiddenDistance));
        $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('distance')
            ->andWhere($latitudeWithin)
            ->andWhere($longitudeWithin)
            ->andWhere(
                $qb->expr()->neq(
                    0,
                    new Func('GEO_DISTANCE_WITHIN', array(
                        $latitude,
                        $longitude,
                        'toponym.latitude',
                        'toponym.longitude',
                        $distanceInKm
                ))))
        ;

        return $qb->getQuery()->execute();
    }

    protected function createToponymQueryBuilder(array $extraSelects = array())
    {
        array_unshift($extraSelects, 'toponym');

        return $this->createQueryBuilder('toponym')->select($extraSelects);
    }
}
