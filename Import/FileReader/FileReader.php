<?php
namespace Giosh94mhz\GeonamesBundle\Import\FileReader;

interface FileReader extends \Countable, \IteratorAggregate
{
    public function open();

    public function close();
}
