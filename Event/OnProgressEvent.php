<?php
namespace Giosh94mhz\GeonamesBundle\Event;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 *
 */
class OnProgressEvent extends ImportEvent
{

    protected $total;

    protected $current;

    public function __construct($total, $current)
    {
        $this->total = $total;
        $this->current = $current;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getCurrent()
    {
        return $this->current;
    }
}
