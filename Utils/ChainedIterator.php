<?php
namespace Giosh94mhz\GeonamesBundle\Utils;

class ChainedIterator implements \Iterator
{
    private $iterators;

    private $i;

    private $count;

    private $nextKey;

    private $keySum;

    public function __construct()
    {
        $this->iterators = array();
        $this->nextKey = 0;
        $this->keySum = 0;
        $this->i = 0;
        $this->count = 0;
    }

    public function append(\Iterator $iterator)
    {
        ++ $this->count;
        $this->iterators[] = $iterator;
    }

    public function valid()
    {
        return $this->i < $this->count && $this->iterators[$this->i]->valid();
    }

    public function next()
    {
        $this->iterators[$this->i]->next();
        $this->key();
        if (! $this->valid())
            $this->nextIterator();
    }

    private function nextIterator()
    {
        while ($this->i < $this->count && ! $this->iterators[$this->i]->valid()) {
            $this->keySum += $this->nextKey;
            $this->nextKey = 0;
            ++ $this->i;
        }
    }

    public function current()
    {
        return $this->iterators[$this->i]->current();
    }

    public function rewind()
    {
        $this->keySum = 0;
        $this->i = 0;
        foreach ($this->iterators as $iterator)
            $iterator->rewind();
    }

    public function key()
    {
        $key = $this->iterators[$this->i]->key();
        if ($this->valid())
            $this->nextKey = $key + 1;

        return $this->keySum + $key;
    }
}
