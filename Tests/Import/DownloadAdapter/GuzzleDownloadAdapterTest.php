<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\DownloadAdapter;

use Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\GuzzleDownloadAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock as BaseMock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Stream\Stream;

class MockBeforeEvent extends BeforeEvent
{
    private $event;

    /**
     * @var ResponseInterface
     */
    public $response;

    public function __construct(BeforeEvent $event)
    {
        $this->event = $event;
    }

    public function getClient()
    {
        return $this->event->getClient();
    }

    public function getRequest()
    {
        return $this->event->getRequest();
    }

    public function getTransaction()
    {
        return $this->event->getTransaction();
    }

    public function intercept(ResponseInterface $response)
    {
        $this->response = $response;
        $this->event->intercept($response);
    }
}

class Mock extends BaseMock
{
    public function onBefore(BeforeEvent $event) {
        $mock = new MockBeforeEvent($event);
        parent::onBefore($mock);

        $save_to = $event->getRequest()->getConfig()->get('save_to');
        if (! empty($save_to))
            file_put_contents($save_to, $mock->response->getBody());
    }
}

/**
 * @requires PHP 5.4.0
 */
class GuzzleDownloadAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected static $directory;

    /**
     * @var GuzzleDownloadAdapter
     */
    protected $download;

    protected $mock;

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

        $this->mock = new Mock(array(), true);
        $client->getEmitter()->attach($this->mock);

        $this->download = new GuzzleDownloadAdapter($client);
        $this->download->setDirectory(self::$directory);
    }

    protected function tearDown()
    {
        if ($this->download) {
            $this->download->clear();
            $this->download = null;
        }
    }

    public function testDownload()
    {
        $text = "this is a sample response";
        $textLength = strlen($text);

        // HEAD Request
        $this->mock->addResponse(new Response(200, array('Content-Length' => $textLength)));
        $this->mock->addResponse(new Response(200, array('Content-Length' => $textLength), Stream::factory($text)));

        $filename = $this->download->add('http://www.example.com/' . mt_rand(10000, 99999));

        $this->download->setProgressFunction(function ($total, $partial) {});
        $this->download->download();

        $size = @filesize($filename);
        $this->assertEquals($textLength, $size);

        return array($filename, $textLength);
    }
}
