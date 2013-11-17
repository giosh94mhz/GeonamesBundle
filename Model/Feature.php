<?php

namespace Giosh94mhz\GeonamesBundle\Model;

interface Feature
{
    const ADMINISTRATIVE_BOUNDARY = 'A';
    const AREA                    = 'L';
    const HYDROGRAPHIC            = 'H';
    const HYPSOGRAPHIC            = 'T';
    const POPULATED_PLACE         = 'P';
    const ROAD                    = 'R';
    const SPOT                    = 'S';
    const UNDERSEA                = 'U';
    const VEGETATION              = 'V';

    public function getClass();

    public function getCode();

    public function getName();

    public function getDescription();
}
