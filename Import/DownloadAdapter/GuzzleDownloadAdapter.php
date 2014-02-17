<?php
namespace Giosh94mhz\GeonamesBundle\Import\DownloadAdapter;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Client;
use Guzzle\Plugin\Cache\CachePlugin;
use Guzzle\Plugin\Cache\DefaultCacheStorage;
use Guzzle\Cache\DoctrineCacheAdapter;
use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Common\Event;

class GuzzleDownloadAdapter extends AbstractDownloadAdapter
{
    /**
     * @var ClientInterface
     */
    protected $client;

    protected $directory;

    protected $requests;

    protected $downloadsSize;

    /**
     * @param ClientInterface $client Client object
     */
    public function __construct(ClientInterface $client = null)
    {
        $this->requests = array();

        if (! $client) {
            $this->client = new Client();

            $cache = new CachePlugin(array(
                'storage' => new DefaultCacheStorage(
                    new DoctrineCacheAdapter(
                        new FilesystemCache(sys_get_temp_dir())
                    )
                )
            ));

            $this->client->addSubscriber($cache);
        } else {
            $this->client = $client;
        }
    }

    public function add($url)
    {
        $destFile = $this->getDestinationPath($url);
        $this->requests[] = array(
            'url' => $url,
            'file' => $destFile
        );
        return $destFile;
    }

    public function requestContentLength()
    {
        if ($this->downloadsSize === null) {
            $requests = array();
            foreach ($this->requests as $request) {
                $requests[] = $this->client->createRequest(
                    'HEAD',
                    $request['url'],
                    array(),
                    null,
                    array()
                );
            }

            $contentLength = 0;
            foreach ($this->client->send($requests) as $response) {
                $contentLength += $response->getContentLength();
            }
            $this->downloadsSize = $contentLength;
        }

        return $this->downloadsSize;
    }

    public function download()
    {
        $progressFunctions = null;
        if ($this->getProgressFunction()) {
            $progressFunctions = $this->createProgressFunctions(
                array_fill(0, count($this->requests), 0)
            );
        }

        $requests = array();
        foreach ($this->requests as $i => $r) {
            $requests[] = $request = $this->client->createRequest(
                'GET',
                $r['url'],
                array(),
                null,
                array('save_to' => $r['file'])
            );
            if ($progressFunctions !== null) {
                // Guzzle progress is too complex for my needs
                $request->getCurlOptions()
                    ->add(CURLOPT_NOPROGRESS, false)
                    ->add(CURLOPT_PROGRESSFUNCTION, $progressFunctions[$i])
                ;
            }
        }

        $this->client->send($requests);
    }

    public function clear()
    {
        $this->requests = array();
    }
}
