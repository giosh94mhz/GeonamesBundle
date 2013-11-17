<?php
namespace Giosh94mhz\GeonamesBundle\Model\Import;

use Giosh94mhz\GeonamesBundle\Utils\CurlDownload;

interface ImportStepBuilder
{
    const GEONAME_DUMP_URL = 'http://download.geonames.org/export/dump/';

    public function download(CurlDownload $download);

    public function getClass();

    public function buildReader();

    public function buildEntity($value);

    public function finalize();
}
