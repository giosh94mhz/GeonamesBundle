<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\FileReader;

use Giosh94mhz\GeonamesBundle\Import\FileReader\CsvIterator;

class CsvIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function providerIterator()
    {
        return array(
            array(
                new CsvIterator(fopen(__DIR__ . '/../../Fixtures/weird.csv', 'r')),
                array('no quote', 'still no quote', '\"weird fgetcsv quote\"', '\\\\weird fgetcsv quote\\\\',
                      '"yes quote"', "Some\ncool\nnewline", "comma,comma", 'UNICODE: ❤€₪₹😁')
            ),
            array(
                new CsvIterator(fopen(__DIR__ . '/../../Fixtures/weird.tsv', 'r'), "\t", null),
                array('"quoted"', 'Mohḏ-yāṟkhēl', 'Mukhammed""yarkheyl\'', 'Mukhammed””yarkheyl’')
            ),
        );
    }

    /**
     * @dataProvider providerIterator
     */
    public function testIterate(CsvIterator $iterator, $result)
    {
        $iterator->next();
        $value = $iterator->current();
        $this->assertEquals($result, $value);

        $iterator->next();
        $this->assertFalse($iterator->valid());
    }

}
