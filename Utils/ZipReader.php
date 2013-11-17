<?php
namespace Giosh94mhz\GeonamesBundle\Utils;

class ZipReader implements FileReader
{
    private $file;
    private $content;

    private $zip;
    private $stream;
    private $size;

    public function __construct($file, $content = null)
    {
        $this->zip = new \ZipArchive();
        $this->file = $file;
        $this->content = $content;

        if (! $content)
            $this->content = substr(basename($file), 0, -3) . 'txt';
    }

    public function open()
    {
        $opened = $this->zip->open($this->file);

        if ($opened !== true)
            throw new \Exception("Unable to open zip file {$this->file} [err={$opened}]");

        $zipInfo = $this->zip->statName($this->content);
        if (! $zipInfo)
            throw new \Exception("Zip file don't contains {$this->content}");

        $this->size = $zipInfo['size'];
        $this->stream = $this->zip->getStream($this->content);
    }

    public function close()
    {
        $this->zip->close();
    }

    public function count()
    {
        return $this->size;
    }

    public function getIterator()
    {
        return new CsvIterator($this->stream, "\t");
    }
}
