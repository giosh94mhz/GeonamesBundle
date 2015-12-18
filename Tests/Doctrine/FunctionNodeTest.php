<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Doctrine;

use Giosh94mhz\GeonamesBundle\Tests\OrmFunctionalTestCase;
use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Doctrine\ORM\Configuration;

class FunctionNodeTest extends OrmFunctionalTestCase
{
    protected function loadFixtures()
    {
        $fixtures = new BaseFixture();
        $fixtures->load($this->_em);
    }

    protected function setUpCustomNumericFunctions(Configuration $config)
    {
        parent::setUpCustomNumericFunctions($config);

        return;

        $config->addCustomNumericFunction('LATITUDE_WITHIN', 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\LatitudeWithin');
        $config->addCustomNumericFunction('LONGITUDE_WITHIN', 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\LongitudeWithin');
    }

    public function testGeoDistance()
    {
        $this->_em->getConfiguration()->addCustomNumericFunction(
            'STD_GEO_DISTANCE',
            'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\GeoDistance'
        );

        $q = $this->_em->createQuery(
            'SELECT STD_GEO_DISTANCE(1.1, 1.2, 2.1, 2.2) AS d FROM Giosh94mhzGeonamesBundle:Toponym t'
        )->setMaxResults(1);

        $expected = 'SELECT 12742.%d * (PI() / 2 - ACOS(SQRT((SIN((1.1 - 2.1)  * PI() / 360) * SIN((1.1 - 2.1)  * PI() / 360)) + (COS(1.1 * PI() / 180) * COS(2.1 * PI() / 180) * SIN((1.2 - 2.2)  * PI() / 360) * SIN((1.2 - 2.2)  * PI() / 360))))) AS %W FROM geoname %W LIMIT 1';
        $this->assertRegExp($this->createPatternFromFormat($expected), $q->getSQL());
    }

    public function testGeoDistanceOnSqlite()
    {
        $this->_em->getConfiguration()->addCustomNumericFunction(
            'SQLITE_GEO_DISTANCE',
            'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\Sqlite\GeoDistance'
        );

        $q = $this->_em->createQuery(
            'SELECT SQLITE_GEO_DISTANCE(1.1, 1.2, 2.1, 2.2) AS d FROM Giosh94mhzGeonamesBundle:Toponym t'
        )->setMaxResults(1);

        $expected = 'SELECT 111.%d * SQRT(((1.1 - 2.1) * (1.1 - 2.1) + (1.2 - 2.2) * (1.2 - 2.2))) AS %W FROM geoname %W LIMIT 1';
        $this->assertRegExp($this->createPatternFromFormat($expected), $q->getSQL());
    }

    public function testGeoDistanceWithin()
    {
        $this->_em->getConfiguration()->addCustomNumericFunction(
            'STD_GEO_DISTANCE_WITHIN',
            'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\GeoDistanceWithin'
        );

        $q = $this->_em->createQuery(
            'SELECT STD_GEO_DISTANCE_WITHIN(1.1, 1.2, 2.1, 2.2, 3.0) AS d FROM Giosh94mhzGeonamesBundle:Toponym t'
        )->setMaxResults(1);

        $expected = 'SELECT (12742.000000 * (PI() / 2 - ACOS(SQRT((SIN((1.1 - 2.1)  * PI() / 360) * SIN((1.1 - 2.1)  * PI() / 360)) + (COS(1.1 * PI() / 180) * COS(2.1 * PI() / 180) * SIN((1.2 - 2.2)  * PI() / 360) * SIN((1.2 - 2.2)  * PI() / 360)))))) <= 3.0 AS %W FROM geoname %W LIMIT 1';
        $this->assertRegExp($this->createPatternFromFormat($expected), $q->getSQL());
    }

    public function testGeoDistanceWithinOnSqlite()
    {
        $this->_em->getConfiguration()->addCustomNumericFunction(
            'SQLITE_GEO_DISTANCE_WITHIN',
            'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\Sqlite\GeoDistanceWithin'
        );

        $q = $this->_em->createQuery(
            'SELECT SQLITE_GEO_DISTANCE_WITHIN(1.1, 1.2, 2.1, 2.2, 3.0) AS d FROM Giosh94mhzGeonamesBundle:Toponym t'
        )->setMaxResults(1);

        $expected = 'SELECT (111.%d * SQRT(((1.1 - 2.1) * (1.1 - 2.1) + (1.2 - 2.2) * (1.2 - 2.2)))) <= 3.0 AS %W FROM geoname %W LIMIT 1';
        $this->assertRegExp($this->createPatternFromFormat($expected), $q->getSQL());
    }

    public function testLatitudeWithin()
    {
        $q = $this->_em->createQuery(
            'SELECT LATITUDE_WITHIN(1.1, 2.1, 3.0) AS d FROM Giosh94mhzGeonamesBundle:Toponym t'
        )->setMaxResults(1);

        $expected = 'SELECT 1.1 BETWEEN 2.1 - 3.0 / 111.%d AND 2.1 + 3.0 / 111.%d AS %W FROM geoname %W LIMIT 1';
        $this->assertRegExp($this->createPatternFromFormat($expected), $q->getSQL());
    }

    public function testLongitudeWithin()
    {
        $q = $this->_em->createQuery(
            'SELECT LONGITUDE_WITHIN(1.1, 2.1, 3.0) AS d FROM Giosh94mhzGeonamesBundle:Toponym t'
        )->setMaxResults(1);

        $expected = 'SELECT 1.1 BETWEEN 2.1 - 3.0 / 111.%d AND 2.1 + 3.0 / 111.%d AS %W FROM geoname %W LIMIT 1';
        $this->assertRegExp($this->createPatternFromFormat($expected), $q->getSQL());
    }

    public function testLongitudeWithinWithLatitude()
    {
        $this->_em->getConfiguration()->addCustomNumericFunction(
            'STD_LONGITUDE_WITHIN',
            'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\LongitudeWithin'
        );

        $q = $this->_em->createQuery(
            'SELECT STD_LONGITUDE_WITHIN(1.1, 1.2, 2.1, 3.0) AS d FROM Giosh94mhzGeonamesBundle:Toponym t'
        )->setMaxResults(1);

        $expected = 'SELECT 1.1 BETWEEN 2.1 - 3.0 / ABS(COS(1.2) * 111.%d) AND 2.1 + 3.0 / ABS(COS(1.2) * 111.%d) AS %W FROM geoname %W LIMIT 1';
        $this->assertRegExp($this->createPatternFromFormat($expected), $q->getSQL());
    }

    public function testLongitudeWithinWithLatitudeOnSqlite()
    {
        $this->_em->getConfiguration()->addCustomNumericFunction(
            'SQLITE_LONGITUDE_WITHIN',
            'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\Sqlite\LongitudeWithin'
        );

        $q = $this->_em->createQuery(
            'SELECT SQLITE_LONGITUDE_WITHIN(1.1, 1.2, 2.1, 3.0) AS d FROM Giosh94mhzGeonamesBundle:Toponym t'
        )->setMaxResults(1);

        $expected = 'SELECT 1.1 BETWEEN 2.1 - 3.0 / 111.%d AND 2.1 + 3.0 / 111.%d AS %W FROM geoname %W LIMIT 1';
        $this->assertRegExp($this->createPatternFromFormat($expected), $q->getSQL());
    }

    public function testDistanceRome()
    {
        $this->loadFixtures();

        /*
         * Note: Venice and Rome are almost on the same langitude, so there is
         * much less error when using flat-surface algorithms
         */

        /* @var $venice \Giosh94mhz\GeonamesBundle\Model\Toponym */
        $venice = $this->_em->find('Giosh94mhzGeonamesBundle:Toponym', 3164603);

        $DQL = <<<DQL
            SELECT GEO_DISTANCE(t.latitude, t.longitude, :latitude, :longitude) AS d
            FROM Giosh94mhzGeonamesBundle:Toponym t
            WHERE t.id = :rome
DQL;

        $result = $this->_em->createQuery($DQL)->execute(array(
            'latitude' => $venice->getLatitude(),
            'longitude' => $venice->getLongitude(),
            'rome' => 3169070
        ));

        $this->assertEquals(393.65, $result[0]['d'], null, 2.0);

        if ($this->_em->getConfiguration()->getCustomNumericFunction('GEO_DISTANCE') != 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\Sqlite\GeoDistance') {
            /* @var $milan \Giosh94mhz\GeonamesBundle\Model\Toponym */
            $milan = $this->_em->find('Giosh94mhzGeonamesBundle:Toponym', 3173435);

            $result = $this->_em->createQuery($DQL)->execute(array(
                'latitude' => $milan->getLatitude(),
                'longitude' => $milan->getLongitude(),
                'rome' => 3169070
            ));

            $this->assertEquals(476.96, $result[0]['d'], null, 2.0);
        }
    }

    public function testDistanceWithinRome()
    {
        $this->loadFixtures();

        /*
         * Note: Venice and Rome are almost on the same langitude, so there is
         * much less error when using flat-surface algorithms
         */

        /* @var $venice \Giosh94mhz\GeonamesBundle\Model\Toponym */
        $venice = $this->_em->find('Giosh94mhzGeonamesBundle:Toponym', 3164603);

        $DQL = <<<DQL
            SELECT
                GEO_DISTANCE_WITHIN(t.latitude, t.longitude, :latitude, :longitude, 390) as isNotWithin,
                GEO_DISTANCE_WITHIN(t.latitude, t.longitude, :latitude, :longitude, 395) as isWithin
            FROM Giosh94mhzGeonamesBundle:Toponym t
            WHERE t.id = :rome
DQL;

        $result = $this->_em->createQuery($DQL)->execute(array(
            'latitude' => $venice->getLatitude(),
            'longitude' => $venice->getLongitude(),
            'rome' => 3169070
        ));

        $this->assertFalse((bool) $result[0]['isNotWithin']);
        $this->assertTrue((bool) $result[0]['isWithin']);
    }

    public function testLatitudeWithinRome()
    {
        $this->loadFixtures();

        // Rome 41,89474±15km => [41.7596,42.0299]
        $DQL = <<<DQL
            SELECT
                LATITUDE_WITHIN(t.latitude, 41.70, 15) as isNotWithin,
                LATITUDE_WITHIN(t.latitude, 42, 15) as isWithin
            FROM Giosh94mhzGeonamesBundle:Toponym t
            WHERE t.id = :rome
DQL;

        $result = $this->_em->createQuery($DQL)->execute(array(
            'rome' => 3169070
        ));

        $this->assertFalse((bool) $result[0]['isNotWithin']);
        $this->assertTrue((bool) $result[0]['isWithin']);
    }

    public function testLongitudeWithinRome()
    {
        $this->loadFixtures();

        // Rome 12.4839±15km => w/o latitude [12.34876,12.61904]
        $DQL = <<<DQL
            SELECT
                LONGITUDE_WITHIN(t.longitude, 12.34875, 15) as isNotWithinMin,
                LONGITUDE_WITHIN(t.longitude, 12.34877, 15) as isWithinMin,
                LONGITUDE_WITHIN(t.longitude, 12.61903, 15) as isWithinMax,
                LONGITUDE_WITHIN(t.longitude, 12.61905, 15) as isNotWithinMax
            FROM Giosh94mhzGeonamesBundle:Toponym t
            WHERE t.id = :rome
DQL;
        $result = $this->_em->createQuery($DQL)->execute(array(
            'rome' => 3169070
        ));

        $this->assertFalse((bool) $result[0]['isNotWithinMin']);
        $this->assertTrue((bool) $result[0]['isWithinMin']);
        $this->assertTrue((bool) $result[0]['isWithinMax']);
        $this->assertFalse((bool) $result[0]['isNotWithinMax']);

        if ($this->_em->getConfiguration()->getCustomNumericFunction('LONGITUDE_WITHIN') != 'Giosh94mhz\GeonamesBundle\Doctrine\FunctionNode\Sqlite\LongitudeWithin') {
            // Rome 12.4839±15km => w/ latitude [12.21038,12.75742]
            $DQL = <<<DQL
                SELECT
                    LONGITUDE_WITHIN(t.longitude, t.latitude, 12.21037, 15) as isNotWithinMin,
                    LONGITUDE_WITHIN(t.longitude, t.latitude, 12.21039, 15) as isWithinMin,
                    LONGITUDE_WITHIN(t.longitude, t.latitude, 12.75741, 15) as isWithinMax,
                    LONGITUDE_WITHIN(t.longitude, t.latitude, 12.75743, 15) as isNotWithinMax
                FROM Giosh94mhzGeonamesBundle:Toponym t
                WHERE t.id = :rome
DQL;
            $result = $this->_em->createQuery($DQL)->execute(array(
                'rome' => 3169070
            ));

            $this->assertFalse((bool) $result[0]['isNotWithinMin']);
            $this->assertTrue((bool) $result[0]['isWithinMin']);
            $this->assertTrue((bool) $result[0]['isWithinMax']);
            $this->assertFalse((bool) $result[0]['isNotWithinMax']);
        }
    }

    protected function createPatternFromFormat($string)
    {
        $string = str_replace(
            array(
                '%e',
                '%s',
                '%S',
                '%a',
                '%A',
                '%w',
                '%i',
                '%d',
                '%x',
                '%f',
                '%c',
                '%W'
            ),
            array(
                '\\' . DIRECTORY_SEPARATOR,
                '[^\r\n]+',
                '[^\r\n]*',
                '.+',
                '.*',
                '\s*',
                '[+-]?\d+',
                '\d+',
                '[0-9a-fA-F]+',
                '[+-]?\.?\d+\.?\d*(?:[Ee][+-]?\d+)?',
                '.',
                '\w+'
            ),
            preg_quote($string, '/')
        );

        return '/^' . $string . '$/s';
    }
}
