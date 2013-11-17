<?php
namespace Giosh94mhz\GeonamesBundle\Utils;

interface FileReader extends \Countable, \IteratorAggregate
{
    public function open();

    public function close();
}
