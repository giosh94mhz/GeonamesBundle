<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\StepBuilder;

use Giosh94mhz\GeonamesBundle\Import\StepBuilder\ToponymImportStepBuilder;
use Giosh94mhz\GeonamesBundle\Tests\Fixtures\BaseFixture;
use Giosh94mhz\GeonamesBundle\Import\FeatureMatch;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class ToponymImportStepBuilderTest extends AbstractImportStepBuilderTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->step = new ToponymImportStepBuilder($this->_em);
        $this->director->addStep($this->step);
    }

    protected function loadFixtures()
    {
        $fixtures = new BaseFixture();
        $fixtures->load($this->_em, true);
    }

    public function testFullImport()
    {
        $this->doDirectorImport();

        $all = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Toponym')->findAll();

        $this->assertGreaterThan(0, count($all));
    }

    public function testImportWithFeatureMatch()
    {
        // include all but A.ADM[1-4]
        $featureMatch = new FeatureMatch(array(
            '*.*'
        ), array(
            'A.*'
        ), array(
            'A.PCLI'
        ));

        $this->step->setFeatureMatch($featureMatch);

        $this->assertSame($featureMatch, $this->step->getFeatureMatch());

        $this->doDirectorImport();

        $all = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Toponym')->findAll();

        $this->assertCount(9, $all);

        foreach ($all as $toponym) {
            /* @var $toponym \Giosh94mhz\GeonamesBundle\Model\Toponym  */
            $feature = $toponym->getFeature();
            $this->assertTrue($featureMatch->isIncluded($feature->getClass(), $feature->getCode()));
        }
    }

    public function testCityImportProvider()
    {
        return array(
            array(17000, 3, true),
            array(6000, 4, false),
            array(1100, 7, false),
        );
    }

    /**
     * @dataProvider testCityImportProvider
     */
    public function testCityImport($population, $expectedCount, $alternateNamesIncluded)
    {
        $this->step->setFeatureMatch(new FeatureMatch(array('P.*')));

        $this->step->setCityPopulation($population);

        $this->assertEquals($population, $this->step->getCityPopulation());

        $this->step->setAlternateNamesIncluded($alternateNamesIncluded);

        $this->assertEquals($alternateNamesIncluded, $this->step->isAlternateNamesIncluded());

        $this->doDirectorImport();

        $all = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Toponym')->findAll();

        $this->assertCount($expectedCount, $all);

        foreach ($all as $toponym) {
            /* @var $toponym \Giosh94mhz\GeonamesBundle\Model\Toponym  */
            $this->assertGreaterThanOrEqual($population, $toponym->getPopulation());
            if (! $alternateNamesIncluded) {
                $this->assertEmpty($toponym->getAlternateNamesArray());
            } else {
                // this is true only with this fixtures, since alternate names may be empty for small cities
                $this->assertNotEmpty($toponym->getAlternateNamesArray());
            }
        }
    }

    public function testCityImportByCountryProvider()
    {
        return array(
            array(array('IT'), 8, false),
            array(array('HM'), 22, false),
            array(array('IT', 'HM'), 30, true),
        );
    }

    /**
     * @dataProvider testCityImportByCountryProvider
     */
    public function testCityImportByCountry($countryCodes, $expectedCount, $alternateCountryCodesIncluded)
    {
        $this->step->setFeatureMatch(new FeatureMatch(array('P.*', 'T.*', 'A.PCLD')));

        $this->step->setCountryCodes($countryCodes);

        $this->assertEquals($countryCodes, $this->step->getCountryCodes());

        $this->step->setAlternateCountryCodesIncluded($alternateCountryCodesIncluded);

        $this->assertEquals($alternateCountryCodesIncluded, $this->step->isAlternateCountryCodesIncluded());

        $this->doDirectorImport();

        $all = $this->_em->getRepository('Giosh94mhzGeonamesBundle:Toponym')->findAll();

        $this->assertCount($expectedCount, $all);

        foreach ($all as $toponym) {
            /* @var $toponym \Giosh94mhz\GeonamesBundle\Model\Toponym  */
            $this->assertContains($toponym->getCountryCode(), $countryCodes);
            if ($toponym->getId() == 1547314) {
                $alternateCountryCodes = $toponym->getAlternateCountryCodes();

                if ($alternateCountryCodesIncluded) {
                    $this->assertNotEmpty($alternateCountryCodes);
                } else {
                    $this->assertEmpty($alternateCountryCodes);
                }
            }
        }
    }

}
