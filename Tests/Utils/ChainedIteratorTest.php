<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Utils;

use Giosh94mhz\GeonamesBundle\Utils\ChainedIterator;

class ChainedIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        $all=array();

        $ci = new ChainedIterator();
        $ci->append( new \ArrayIterator(array(0, 1, 2 , 3, 4)) );
        $ci->append( new \ArrayIterator(array(5, 6, 7, 8 , 9)) );
        $all[] = array(
            $ci,
            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9)
        );

        $ci = new ChainedIterator();
        $ci->append( new \ArrayIterator(array(0, 2, 4, 6, 8)) );
        $ci->append( new \ArrayIterator(array(10, 12, 14, 16 , 18)) );
        $all[] = array(
            $ci,
            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            array(0, 2, 4, 6, 8, 10, 12, 14, 16, 18)
        );

        $ci = new ChainedIterator();
        $ci->append( new \ArrayIterator(array(0, 2, 4, 6, 8)) );
        $ci->append( new \ArrayIterator(array()) );
        $ci->append( new \ArrayIterator(array()) );
        $ci->append( new \ArrayIterator(array(10, 12, 14, 16, 18)) );
        $all[] = array(
            $ci,
            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            array(0, 2, 4, 6, 8, 10, 12, 14, 16, 18)
        );

        $ci = new ChainedIterator();
        $ci->append( new \ArrayIterator(array()) );
        $ci->append( new \ArrayIterator(array()) );
        $ci->append( new \ArrayIterator(array(0, 2, 4, 6, 8)) );
        $ci->append( new \ArrayIterator(array(10, 12, 14, 16, 18)) );
        $all[] = array(
            $ci,
            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            array(0, 2, 4, 6, 8, 10, 12, 14, 16, 18)
        );

        $ci = new ChainedIterator();
        $ci->append( new \ArrayIterator(array(0, 2, 4, 6, 8)) );
        $ci->append( new \ArrayIterator(array(10, 12, 14, 16, 18)) );
        $ci->append( new \ArrayIterator(array()) );
        $ci->append( new \ArrayIterator(array()) );
        $all[] = array(
            $ci,
            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            array(0, 2, 4, 6, 8, 10, 12, 14, 16, 18)
        );

        $ci = new ChainedIterator();
        $ci->append( new \ArrayIterator(array(0 => 0, 2 => 2, 4 => 4, 6 => 6, 8 => 8)) );
        $ci->append( new \ArrayIterator(array(0 => 10, 2 => 12, 4 => 14, 6 => 16, 8 => 18)) );
        $ci->append( new \ArrayIterator(array()) );
        $ci->append( new \ArrayIterator(array()) );
        $all[] = array(
            $ci,
            array(0, 2, 4, 6, 8, 9, 11, 13, 15, 17),
            array(0, 2, 4, 6, 8, 10, 12, 14, 16, 18)
        );

        return $all;
    }

    /**
     * @dataProvider provider
     */
    public function testForeachValues(ChainedIterator $iterator, $keys, $values)
    {
        $i = 0;
        foreach ($iterator as $value) {
            $this->assertEquals($values[$i], $value);
            ++ $i;
        }
    }

    /**
     * @dataProvider provider
     */
    public function testForeachKeys(ChainedIterator $iterator, $keys, $values)
    {
        $collectedKeys = array();
        $i = 0;
        foreach ($iterator as $key => $value) {
            $collectedKeys[] = $key;
            $this->assertEquals($keys[$i], $key, "Key mismatch: found ".json_encode($collectedKeys).', expected: '.json_encode($keys));
            ++ $i;
        }
    }
}
