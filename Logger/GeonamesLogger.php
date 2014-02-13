<?php
namespace Giosh94mhz\GeonamesBundle\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 *
 */
class GeonamesLogger extends AbstractLogger
{
    protected $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /*
     * (non-PHPdoc)
     * @see \Psr\Log\LoggerInterface::log()
     */
    public function log($level, $message, array $context = array())
    {
        if ($this->logger)
            $this->logger->log($level, $message, $context);
    }
}
