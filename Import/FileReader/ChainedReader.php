<?php
namespace Giosh94mhz\GeonamesBundle\Import\FileReader;

class ChainedReader implements FileReader
{
    private $readers;

    public function __construct()
    {
        $this->readers = array();
    }

    public function append(FileReader $reader)
    {
        $this->readers[] = $reader;
    }

    public function open()
    {
        foreach ($this->readers as $reader)
            $reader->open();
    }

    public function count()
    {
        $count = 0;
        foreach ($this->readers as $reader)
            $count += $reader->count();

        return $count;
    }

    public function getIterator()
    {
        $iterator = new ChainedIterator();
        foreach ($this->readers as $reader)
            $iterator->append($reader->getIterator());

        return $iterator;
    }

    public function close()
    {
        foreach ($this->readers as $reader)
            $reader->close();
    }
}
