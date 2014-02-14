<?php
namespace Giosh94mhz\GeonamesBundle\Model\Import;

use Giosh94mhz\GeonamesBundle\Utils\CurlDownload;

interface ImportStepBuilder
{
    public function download(CurlDownload $download);

    public function getClass();

    public function buildReader();

    public function buildEntity($value);

    public function finalize();
}
