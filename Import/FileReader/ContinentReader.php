<?php
namespace Giosh94mhz\GeonamesBundle\Import\FileReader;

class ContinentReader extends TxtReader
{
    private $values;

    public function open()
    {
        $this->values = array();
        $this->openStream();

        $foundStart=false;
        while (($line = fgets($this->stream)) !== false) {

            // skip heading lines
            if (!$foundStart) {
                if (preg_match('/Continent\s+codes\s*[:]/i', $line))
                    $foundStart=true;
                continue;
            }

            $line=trim($line);

            // skip trailing lines
            if (empty($line))
                break;

            if (!preg_match('/([A-Z]+)[ :\t]+([^\t]+)[^0-9]+([0-9]+)/', $line, $matches))
                throw new \Exception("Cannot parse {$file}");

            array_shift($matches);

            $this->values[]=$matches;
        }

        $this->size = count($this->values);

        parent::close();
    }

    public function close()
    {
        $this->values = null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }
}
