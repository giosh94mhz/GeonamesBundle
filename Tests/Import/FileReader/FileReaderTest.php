<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\FileReader;

use Giosh94mhz\GeonamesBundle\Import\FileReader\TxtReader;
use Giosh94mhz\GeonamesBundle\Import\FileReader\ZipReader;
use Giosh94mhz\GeonamesBundle\Import\FileReader\ContinentReader;
use Giosh94mhz\GeonamesBundle\Import\FileReader\ChainedReader;

class FileReaderTest extends \PHPUnit_Framework_TestCase
{
    public function readerProvider()
    {
        $fixturesDir = __DIR__ . '/../../Fixtures/';

        $chained = new ChainedReader();
        $chained->append(new TxtReader($fixturesDir. 'admin1CodesASCII.txt'));
        $chained->append(new TxtReader($fixturesDir. 'admin2Codes.txt'));

        return array(
            array(
                new TxtReader($fixturesDir. 'admin2Codes.txt'),
                array('IT.09.CO', 'Provincia di Como', 'Provincia di Como', '3178227')
            ),
            array(
                new TxtReader($fixturesDir. 'admin2Codes.txt'),
                array('IT.09.CO', 'Provincia di Como', 'Provincia di Como', '3178227')
            ),
            array(
                new ZipReader($fixturesDir. 'allCountries.zip'),
                array('6255146', 'Africa', 'Africa', 'Affrica,Africa,Afrihkka,Afrihkká,Afrika,Afrikka,Afriko,Afrique,Afryka,Afríka,Aphrike,Chau Phi,Châu Phi,afrika,afryqa,afryqya,afurika,an Afraic,apeulika,el Continente Negro,fei zhou,Àfrica,África,Āfrika,Αφρική,Африка,אפריקה,أفريقيا,افریقا,अफ़्रीका,แอฟริกา,アフリカ,非洲,아프리카', '7.1881', '21.09375', 'L', 'CONT', '', '', '', '', '', '', '0', '', '592', 'Africa/Bangui', '2013-02-13')
            ),
            array(
                new ContinentReader($fixturesDir. 'readme.txt'),
                array('AF', 'Africa', '6255146')
            ),
            array(
                $chained,
                array('IT.09', 'Lombardy', 'Lombardy', '3174618')
            ),
        );
    }

    /**
     * @dataProvider readerProvider
     */
    public function testOpenClose($reader, $firstValue)
    {
        $reader->open();
        $this->assertTrue(true);

        $reader->close();
        $this->assertTrue(true);
    }

    /**
     * @dataProvider readerProvider
     */
    public function testIterate($reader, $firstValue)
    {
        $reader->open();

        $count = $reader->count();
        $this->assertGreaterThan(0, $count);

        $values = array();
        $lastKey = 0;
        foreach ($reader as $key => $value) {
            $values[] = $value;
            $lastKey = $key;
        }

        $this->assertEquals($count, $lastKey + 1);
        $this->assertEquals($firstValue, $values[0]);

        $reader->close();
    }
}
