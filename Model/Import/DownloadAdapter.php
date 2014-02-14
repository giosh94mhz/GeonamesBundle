<?php

namespace Giosh94mhz\GeonamesBundle\Model\Import;

/**
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
interface DownloadAdapter
{
    public function getDirectory();

    public function setDirectory($directory);

    public function add($url);

    public function requestContentLength();

    public function download();

    public function clear();

    /**
     * Set a callback for download progress.
     *
     * @param Closure $progressFunction Two params are required: $download_size, $downloaded
     *
     * @return string
     */
    public function setProgressFunction(\Closure $progressFunction);
}
