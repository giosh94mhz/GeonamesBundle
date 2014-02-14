<?php
namespace Giosh94mhz\GeonamesBundle\Model\Import;

interface ImportStepBuilder
{
    public function download(DownloadAdapter $download);

    public function getClass();

    public function buildReader();

    public function buildEntity($value);

    public function finalize();
}
