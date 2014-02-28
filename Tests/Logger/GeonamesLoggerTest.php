<?php
namespace Giosh94mhz\GeonamesBundle\Tests\Logger;

use Giosh94mhz\GeonamesBundle\Logger\GeonamesLogger;
use Psr\Log\LogLevel;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
class GeonamesLoggerTest extends \PHPUnit_Framework_TestCase
{
    public function testDisabledLogger()
    {
        $logger = new GeonamesLogger();

        // should not fail or throw without a logger instance
        $logger->log(LogLevel::DEBUG, "log", array('context'));

        $this->assertTrue(true);
    }

    public function testLogger()
    {
        $mockLogger = $this->getMock('\Psr\Log\LoggerInterface');
        $mockLogger
            ->expects($this->once())
            ->method('log')
            ->with(LogLevel::DEBUG, 'log', array('context'))
        ;

        $logger = new GeonamesLogger($mockLogger);
        $logger->log(LogLevel::DEBUG, 'log', array('context'));
    }
}
