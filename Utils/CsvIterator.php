<?php
namespace Giosh94mhz\GeonamesBundle\Utils;

class CsvIterator implements \Iterator
{
    private $stream;
    private $current;
    private $valid;
    private $delimiter;
    private $enclosure;
    private $escape;
    private $comment;
    private $read;

    public function __construct($stream, $delimiter = ',', $enclosure = '"', $escape = '\\', $comment = '#')
    {
        $this->stream = $stream;
        $this->delimiter=$delimiter;
        $this->enclosure=$enclosure;
        $this->escape=$escape;
        $this->comment=$comment;
        $this->valid = !feof($this->stream);
        $this->read = 0;
    }

    public function valid()
    {
        return $this->valid;
    }

    public function next()
    {
        // skip empty lines and comments
        do {
            $this->current=fgetcsv($this->stream, 0, $this->delimiter, $this->enclosure, $this->escape);
            $skipLine = is_array($this->current)
                        && ((! isset ($this->current[0]))
                             || (isset ($this->current[0][0]) && $this->current[0][0] == $this->comment));
        } while ($skipLine);

        if( !$this->current )
            $this->valid = false;
        $this->read = ftell($this->stream) - 1;
    }

    public function current()
    {
        return $this->current;
    }

    public function rewind()
    {
        if( ftell($this->stream) !== 0 )
            rewind($this->stream);
        $this->next();
    }

    public function key()
    {
        return $this->read;
    }
}
