<?php
namespace Giosh94mhz\GeonamesBundle\Import\FileReader;

class TxtReader implements FileReader
{
    protected $file;
    protected $stream;
    protected $size;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function open()
    {
        $this->openStream();

        fseek($this->stream, 0, SEEK_END);
        $this->size = ftell($this->stream);
        rewind($this->stream);
    }

    protected function openStream()
    {
        if (! ($fd = fopen($this->file, 'r')))
            throw new \Exception("Unable to open stream: {$this->file}");
        $this->stream = $fd;
    }

    public function close()
    {
        if ($this->stream)
            fclose($this->stream);
        $this->stream = null;
    }

    public function count()
    {
        return $this->size;
    }

    public function getIterator()
    {
        return new CsvIterator($this->stream, "\t", null);
    }
}
