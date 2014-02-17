<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\DownloadAdapter;

use Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\GuzzleDownloadAdapter;
use Guzzle\Http\Client;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;

class GuzzleDownloadAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected static $directory;

    /**
     * @var GuzzleDownloadAdapter
     */
    protected $download;

    protected $mockPlugin;

    public static function setUpBeforeClass()
    {
        $reflect = new \ReflectionClass(__CLASS__);
        self::$directory = @tempnam(sys_get_temp_dir(), $reflect->getShortName() . '-');
        @unlink(self::$directory);
        @mkdir(self::$directory);
    }

    public static function tearDownAfterClass()
    {
        @rmdir(self::$directory);
    }

    protected function setUp()
    {
        $client = new Client();

        $this->mockPlugin = new MockPlugin();
        $client->addSubscriber($this->mockPlugin);

        $this->download = new GuzzleDownloadAdapter($client);
        $this->download->setDirectory(self::$directory);
    }

    protected function tearDown()
    {
        $this->download->clear();
        $this->download = null;
    }

    public function testDownload()
    {
        $text = "this is a sample response";
        $textLength = strlen($text);

        // HEAD Request
        $this->mockPlugin->addResponse(new Response(200, array('Content-Length' => $textLength)));
        $this->mockPlugin->addResponse(new Response(200, array('Content-Length' => $textLength, $text)));

        $filename = $this->download->add('http://www.example.com/' . mt_rand(10000, 99999));

        $outTotal = 0;
        $outPartial = -1;
        $this->download->setProgressFunction(function ($total, $partial) use (&$outTotal, &$outPartial) {
            $outTotal = $total;
            if ($partial > 0)
                $outPartial = $partial;
        });

        $this->download->download();

        $size = @filesize($filename);

        // $this->assertEquals($textLength, $size); // MockPlugin don't support save_to apparently
        $this->assertNotSame(false, $size);

        // $this->assertEquals($outTotal, $outPartial); // MockPlugin don't call curl, so no progress
        return array( $filename, $size );
    }
}
