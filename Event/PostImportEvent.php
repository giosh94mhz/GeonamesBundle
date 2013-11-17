<?php
namespace Giosh94mhz\GeonamesBundle\Event;

/**
 *
 * @author giosh
 *
 */
class PostImportEvent extends ImportEvent
{

    protected $synced;

    protected $skipped;

    protected $errors;

    public function __construct($synced, $skipped, $errors)
    {
        $this->synced = $synced;
        $this->skipped = $skipped;
        $this->errors = $errors;
    }

    public function getSynced()
    {
        return $this->synced;
    }

    public function getSkipped()
    {
        return $this->skipped;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getTotal()
    {
        return $this->synced + $this->skipped + $this->errors;
    }

}
