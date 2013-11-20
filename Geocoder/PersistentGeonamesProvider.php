<?php
namespace Giosh94mhz\GeonamesBundle\Geocoder;

use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Provider\LocaleAwareProviderInterface;
use Geocoder\Exception\NoResultException;
use Geocoder\Exception\UnsupportedException;

class PersistentGeonamesProvider implements LocaleAwareProviderInterface
{

    protected $em;

    protected $locale;

    public function __construct(EntityManagerInterface $em, $locale = null)
    {
        $this->em = $em;
        $this->locale = $locale;
    }

    /**
     * {@inheritDoc}
     */
    public function getGeocodedData($address)
    {
        // This API doesn't handle IPs
        if (filter_var($address, FILTER_VALIDATE_IP)) {
            throw new UnsupportedException('The PersistentGeonamesProvider does not support IP addresses.');
        }

        $dql = <<<DQL
SELECT t
FROM Giosh94mhzGeonamesBundle:Toponym t
WHERE t.name LIKE :name
DQL;

        /* @var $query \Doctrine\ORM\Query */
        $query = $this->em->createQuery($dql);

        /* @var $toponym \Giosh94mhz\GeonamesBundle\Entity\Toponym */
        $toponym = $query->setParameter('name', "%{$address}%")
                         ->setMaxResults(1)
                         ->getOneOrNullResult();
        if (! $toponym)
            throw new NoResultException();

        return array(
            'geonameid' => $toponym->getId(),
            'toponym' => $toponym
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return "persistent_geonames";
    }

    /**
     * {@inheritDoc}
     */
    public function getReversedData(array $coordinates)
    {
        $dql = <<<DQL
SELECT t
FROM Giosh94mhzGeonamesBundle:Toponym t
WHERE
    :boxLatMin < d.latitude AND d.latitude < :boxLatMax
    AND
    :boxLonMin < d.longitude AND d.longitude < :boxLonMax
ORDER BY
    (d.latitude-:latitude)*(d.latitude-:latitude)
    +(d.longitude-:longitude)*(d.longitude-:longitude)
LIMIT 1

DQL;

        /* @var $query \Doctrine\ORM\Query */
        $query = $this->em->createQuery($dql);
        $results = $query->setParameter('name', "%{$address}%")->getResult();
        if (empty($results))
            throw new NoResultException();

            /* @var $toponym \Giosh94mhz\GeonamesBundle\Entity\Toponym */
        $toponym = $results[0];

        return array(
            'geonameid' => $toponym->getId(),
            'toponym' => $toponym
        );
    }
}
