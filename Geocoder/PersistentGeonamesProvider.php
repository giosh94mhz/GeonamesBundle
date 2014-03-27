<?php
namespace Giosh94mhz\GeonamesBundle\Geocoder;

use Doctrine\Common\Persistence\ObjectManager;
use Geocoder\Provider\LocaleAwareProviderInterface;
use Geocoder\Exception\NoResultException;
use Geocoder\Exception\UnsupportedException;
use Geocoder\Provider\ProviderInterface;
use Geocoder\Geocoder;
use Giosh94mhz\GeonamesBundle\Model\Toponym;

class PersistentGeonamesProvider implements LocaleAwareProviderInterface
{
    protected $om;

    protected $locale;

    protected $maxResults;

    protected $ipProvider;

    public function __construct(ObjectManager $om, $locale = null)
    {
        $this->om = $om;
        $this->locale = $locale ?: 'en';
        $this->maxResults = Geocoder::MAX_RESULTS;
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
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritDoc}
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * {@inheritDoc}
     */
    public function getGeocodedData($address)
    {
        if (filter_var($address, FILTER_VALIDATE_IP)) {
            return $this->getGeocodedIpData($address);
        }

        // Find by address
        $dql = <<<DQL
SELECT t
FROM Giosh94mhzGeonamesBundle:Toponym t
WHERE t.name LIKE :name
DQL;

        /* @var $query \Doctrine\ORM\Query */
        $query = $this->om->createQuery($dql);

        $toponyms = $query
            ->setParameter('name', "%{$address}%")
            ->setMaxResults($this->maxResults)
            ->execute()
        ;
        if (empty($toponyms))
            throw new NoResultException();

        return $this->toponymsToResultArray($toponyms);
    }

    private function getGeocodedIpData($address)
    {
        // This API doesn't handle IPs directly
        if (! $this->ipProvider) {
            throw new UnsupportedException(
                __CLASS__ . 'does not support IP addresses. Use \'setIpProvider\' to chain a Provider (for IP only).'
            );
        }

        $data = $this->ipProvider->getGeocodedData($address);
        if (empty($data))
            throw new NoResultException();

        $result = $this->getReversedGeocoding(
            $data[0]['latitude'],
            $data[0]['longitude'],
            isset($data[0]['countryCode']) ? $data[0]['countryCode'] : null,
            1
        );

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getReversedData(array $coordinates)
    {
        return $this->getReversedGeocoding($coordinates[0], $coordinates[1]);
    }

    private function getReversedGeocoding($latitude, $longitude, $countryCode = null,  $maxResults = null)
    {
        /* @var $repo \Giosh94mhz\GeonamesBundle\Model\Repository\ToponymRepository */
        $repo = $this->om->getRepository('Giosh94mhzGeonamesBundle:Toponym');

        $maxResults = $maxResults ?: $this->maxResults;

        $toponyms = $repo->findPlacesByDistance($latitude, $longitude, 10, $countryCode, $maxResults);
        if (empty($toponyms))
            throw new NoResultException();

        return $this->toponymsToResultArray($toponyms);
    }

    public function getIpProvider()
    {
        return $this->ipProvider;
    }

    public function setIpProvider(ProviderInterface $ipProvider)
    {
        $this->ipProvider = $ipProvider;

        return $this;
    }

    protected function toponymsToResultArray($toponyms)
    {
        return array_map(function (Toponym $toponym) {
            return array(
                'geonameid' => $toponym->getId(),
                'toponym' => $toponym
            );
        }, $toponyms);
    }
}
