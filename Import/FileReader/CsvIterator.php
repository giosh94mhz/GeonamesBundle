<?php
namespace Giosh94mhz\GeonamesBundle\Import\FileReader;

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

    /**
     * Create an interator from a stream.
     *
     * @param mixed $stream PHP stream (file, stream, socket, ...)
     * @param string $delimiter Character to separate column
     * @param string $enclosure Character used to "enclose" a column, or null
     * @param string $escape Escape character for the enclosure character, or null
     * @param string $comment Characted which identify comment lines, or null
     * @note This rely on the PHP function fgetcsv, so it carries on all it's weirdness
     *       In particular, to escape the (opening) $enclusure char you should prepend the $escape
     *       char, but to escape the (closing) $enclosure char inside a column, you should write
     *       the enclosure char twice. Also note that the combination \" and \\ are always copied as-is no
     *       metter what.
     */
    public function __construct($stream, $delimiter = ',', $enclosure = '"', $escape = '\\', $comment = '#')
    {
        $this->stream = $stream;
        $this->delimiter = $delimiter;
        if ($enclosure === null) {
            $this->enclosure = $this->escape = "\0";
        } else {
            $this->enclosure = $enclosure;
            $this->escape = $escape ?: "\0";
        }
        $this->comment = $comment;
        $this->valid = !feof($this->stream);
        $this->read = 0;
    }

    public function valid()
    {
        return $this->valid;
    }

    public function next()
    {
        do {
            $this->current = fgetcsv($this->stream, 0, $this->delimiter, $this->enclosure, $this->escape);
        } while ($this->skipCurrentLine());

        if (! $this->current)
            $this->valid = false;
        $this->read = ftell($this->stream) - 1;
    }

    private function skipCurrentLine()
    {
        return is_array($this->current) && (
            (count($this->current) === 1 && $this->current[0] === null)
            || (isset($this->current[0][0]) && $this->current[0][0] === $this->comment)
        );
    }

    public function current()
    {
        return $this->current;
    }

    public function rewind()
    {
        if (ftell($this->stream) !== 0)
            rewind($this->stream);
        $this->next();
    }

    public function key()
    {
        return $this->read;
    }
}
