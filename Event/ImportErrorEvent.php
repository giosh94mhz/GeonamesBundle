<?php
namespace Giosh94mhz\GeonamesBundle\Event;

class ImportErrorEvent extends ImportEvent
{
    protected $class;

    protected $value;

    protected $exception;

    public function __construct($class, $value, \Exception $exception)
    {
        $this->class = $class;
        $this->value = $value;
        $this->exception = $exception;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getException()
    {
        return $this->exception;
    }

}
