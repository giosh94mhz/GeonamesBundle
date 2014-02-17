<?php

namespace Giosh94mhz\GeonamesBundle\Import\DownloadAdapter;

use Giosh94mhz\GeonamesBundle\Exception\ExtensionNotLoadedException;

/**
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class CurlDownloadAdapter extends AbstractDownloadAdapter
{
    private static $partSuffix = '.part';

    private $channels;
    private $files;
    private $downloadsSize;

    private $useCache;

    public function __construct()
    {
        $this->channels = array();
        $this->files = array();
        $this->useCache = true;

        if (! function_exists('curl_init')) {
            throw new ExtensionNotLoadedException('cURL has to be enabled.');
        }
    }

    public function getUseCache()
    {
        return $this->useCache;
    }

    public function setUseCache($useCache)
    {
        $this->useCache = (boolean) $useCache;

        return $this;
    }

    public function add($url, $destFile = null)
    {
        $this->downloadsSize = null;

        if( $destFile === null )
            $destFile = $this->getDestinationPath($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $mtime = null;
        if ($this->useCache) {
            if ( ($mtime = @filemtime($destFile)) ) {
                curl_setopt($ch, CURLOPT_TIMECONDITION, CURL_TIMECOND_IFMODSINCE);
                curl_setopt($ch, CURLOPT_TIMEVALUE, $mtime);
            }
        }

        $this->channels[]=$ch;
        $this->files[]=array(
            'fd' => null,
            'path' => $destFile,
            'downloaded' => 0,
            'mtime' => $mtime
        );

        return $destFile;
    }

    public function requestContentLength()
    {
        if ($this->downloadsSize === null) {
            $chCopy=array();
            foreach ($this->channels as $ch) {
                $chCopy[] = $ch = curl_copy_handle($ch);

                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            }

            $this->curlMultiDownload($chCopy);

            $this->downloadsSize = 0.;
            foreach ($chCopy as $ch) {
                $length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                if ($length > 0)
                    $this->downloadsSize += $length;
                curl_close($ch);
            }
        }

        return $this->downloadsSize;
    }

    /**
     * Returns the content fetched from a given URL.
     *
     * @param string $url
     *
     * @return string
     */
    public function download()
    {
        $progressFunctions = null;

        if ($this->getProgressFunction()) {
            $init = array_fill(0, count($this->files), 0);
            foreach ($this->files as $i => $file)
                $init[$i] = $file['downloaded'];

            $progressFunctions = $this->createProgressFunctions($init);
        }

        foreach ($this->channels as $i => $ch) {
            curl_setopt($ch, CURLOPT_FILE, $this->openPartFile($this->files[$i]) );

            if( $this->files[$i]['downloaded'] )
                curl_setopt($ch, CURLOPT_RANGE, $this->files[$i]['downloaded'] . '-');

            if ($progressFunctions) {
                curl_setopt($ch, CURLOPT_NOPROGRESS, false);
                curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, $progressFunctions[$i]);
            }
        }

        $results = $this->curlMultiDownload($this->channels);

        foreach ($results as &$result) {
            $i = array_search($result['ch'], $this->channels);
            $result['file'] = $this->files[$i]['path'];
            unset( $result['ch'] );

            $result['content_length'] += $this->files[$i]['downloaded'];

            $this->closePartFile($this->files[$i]);

            if ( intval($result['http_code']/100) == 2) {
                $this->finalizePartFile($this->files[$i]);
            } else {
                $this->deletePartFile($this->files[$i]);
            }
        }

        $this->clear();
    }

    private function openPartFile(&$file)
    {
        $path = $file['path'] . self::$partSuffix;

        $downloadedSize = @filesize($path) ?: 0;

        if ( !($fd = @fopen($path, $downloadedSize? 'a' : 'w+')) )
            throw new \Exception('fopen failed');

        $file['downloaded'] = $downloadedSize;

        return $file['fd'] = $fd;
    }

    private function closePartFile(&$file)
    {
        if( $file['fd'] && !@fclose($file['fd']) )
            throw new \Exception('fclose failed');
        $file['fd'] = null;

        return true;
    }

    private function finalizePartFile(&$file)
    {
        if (true !== @rename($file['path'] . self::$partSuffix, $file['path'])) {
            throw new \Exception(sprintf('Cannot rename "%s" to "%s".', $file['path'] . self::$partSuffix, $file['path']));
        }
    }

    private function deletePartFile(&$file)
    {
        return @unlink($file['path'] . self::$partSuffix);
    }

    public function clear()
    {
        foreach ($this->channels as $i => $ch) {
            curl_close($ch);
            $this->closePartFile($this->files[$i]);
        }

        $this->channels=array();
        $this->files=array();
        $this->downloadsSize=null;
    }

    protected function curlMultiDownload(array $chs)
    {
        $mh = curl_multi_init();

        foreach ($chs as $ch) {
            curl_multi_add_handle($mh, $ch);
        }

        $results = array();
        $running = null;
        do {
            curl_multi_exec($mh, $running);

            while ( ($mInfo = curl_multi_info_read($mh)) ) {
                if( $mInfo['result'] != CURLE_OK )
                    throw new \Exception('curl_multi_info_read result != CURLE_OK.');

                $httpCode = curl_getinfo($mInfo['handle'], CURLINFO_HTTP_CODE);
                $result = array(
                    'ch' => $mInfo['handle'],
                    'http_code' => $httpCode,
                    'content_type' => curl_getinfo($mInfo['handle'], CURLINFO_CONTENT_TYPE),
                    'content_length' => curl_getinfo($mInfo['handle'], CURLINFO_CONTENT_LENGTH_DOWNLOAD)
                );
                if ( intval($httpCode/100) == 4 || intval($httpCode/100) == 5 ) {
                    curl_multi_remove_handle($mh, $mInfo['handle']);
                    $result['content_type'] = 'inode/x-empty';
                    $result['content_length'] = 0;
                }

                $results[] = $result;
            }

            $ready = curl_multi_select($mh);
        } while ($running > 0 && $ready != -1);

        curl_multi_close($mh);

        return $results;
    }
}
