<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Import\DownloadAdapter;

use Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter;

class CurlDownloadAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CurlDownloadAdapter
     */
    protected $download;

    protected function setUp()
    {
        $this->download = new CurlDownloadAdapter();
    }

    protected function tearDown()
    {
        $this->download->clear();
        $this->download = null;
    }

    /**
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::getDirectory
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::setDirectory
     */
    public function testDirectory()
    {
        $filename = 'unitest-geonames-' . mt_rand(1000000, 9999999);
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;

        $this->assertEquals($this->download, $this->download->setDirectory($dir));
        $this->assertEquals($dir, $this->download->getDirectory());

        $this->assertEquals($dir . $filename, $this->download->add('http://www.example.com/'.$filename));
    }

    /**
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::getUseCache
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::setUseCache
     */
    public function testUseCache()
    {
        $this->assertEquals($this->download, $this->download->setUseCache(true));
        $this->assertEquals(true, $this->download->getUseCache());

        $this->assertEquals($this->download, $this->download->setUseCache(false));
        $this->assertEquals(false, $this->download->getUseCache());
    }

    /**
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::add
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::setProgressFunction
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::download
     */
    public function testDownload()
    {
        $filename = tempnam(sys_get_temp_dir(), 'unitest-geonames-');

        $this->download->setUseCache(false);

        $this->assertEquals($filename, $this->download->add('http://www.example.com/', $filename));

        $outTotal = 0;
        $outPartial = -1;
        $this->download->setProgressFunction(function ($total, $partial) use (&$outTotal, &$outPartial) {
            $outTotal = $total;
            if ($partial > 0)
                $outPartial = $partial;
        });

        $this->download->download();

        $size = filesize($filename);

        $this->assertGreaterThan(0, $size);

        $this->assertEquals($outTotal, $outPartial);

        return array( $filename, $size );
    }

    /**
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::requestContentLength
     * @depends testDownload
     */
    public function testRequestContentLength(array $in)
    {
        list( $filename, $size ) = $in;

        $this->download->setUseCache(false);
        $this->download->add('http://www.example.com/', $filename);

        $this->assertEquals($size, $this->download->requestContentLength());

        return $in;
    }

    /**
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::download
     * @depends testRequestContentLength
     */
    public function testCachedDownload(array $in)
    {
        list( $filename, $size ) = $in;

        $this->download->add('http://www.example.com/', $filename);

        $this->download->setUseCache(true);

        $cacheUsed = true;
        $this->download->setProgressFunction(function ($total, $partial) use (&$cacheUsed) {
            if ($partial > 0)
                $cacheUsed = false;
        });

        $this->download->download();

        $this->assertEquals(true, $cacheUsed);

        unlink($filename);
    }

    /**
     * @covers Giosh94mhz\GeonamesBundle\Import\DownloadAdapter\CurlDownloadAdapter::download
     */
    public function testMultiDownload()
    {
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        $filenames = array();

        $this->download->setUseCache(false);
        $this->download->setDirectory($directory);

        foreach (array('com', 'org', 'net') as $tld) {
            $filename = tempnam($directory, 'unitest-geonames-');
            $filenames[] = $this->download->add("http://www.example.{$tld}/", $filename);
        }

        $this->download->download();

        foreach ($filenames as $filename) {
            $size = filesize($filename);
            $this->assertGreaterThan(0, $size);
            unlink($filename);
        }
    }
}
