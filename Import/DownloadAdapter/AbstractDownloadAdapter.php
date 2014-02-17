<?php

namespace Giosh94mhz\GeonamesBundle\Import\DownloadAdapter;

use Giosh94mhz\GeonamesBundle\Model\Import\DownloadAdapter;

/**
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
abstract class AbstractDownloadAdapter implements DownloadAdapter
{
    private $directory;

    private $progressFunction;

    public function getDirectory()
    {
        return $this->directory;
    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Set a callback for download progress.
     *
     * @param Closure $progressFunction Two params are required: $download_size, $downloaded
     *
     * @return string
     */
    public function setProgressFunction(\Closure $progressFunction)
    {
        $this->progressFunction = $progressFunction;

        return $this;
    }

    public function getProgressFunction()
    {
        return $this->progressFunction;
    }

    protected function createProgressFunctions(array $init)
    {
        $callback = $this->getProgressFunction();
        if (! $callback)
            return null;

        $fileCount = count($init);

        $totalSize = $this->requestContentLength();

        $partialSizes = array_fill(0, $fileCount, 0);

        $aggregate = function ($i, $partial) use ($callback, $totalSize, &$init, &$partialSizes) {
            $partialSizes[$i] = $init[$i] + $partial;
            call_user_func($callback, $totalSize, min(array_sum($partialSizes), $totalSize));
        };

        $map = array();
        for ($i = 0; $i < $fileCount; ++ $i) {
            $map[] = function ($total, $partial) use ($i, $aggregate) {
                call_user_func($aggregate, $i, $partial);
            };
        }

        return $map;
    }

    protected function getDestinationPath($url)
    {
        $dir = $this->directory ?: getcwd();
        $parts = explode('?', $url, 2);

        return rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($parts[0]);
    }
}
