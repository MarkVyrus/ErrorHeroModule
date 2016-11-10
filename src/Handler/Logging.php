<?php

namespace ErrorHeroModule\Handler;

use Error;
use Exception;
use Zend\Log\Logger;

class Logging
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $e
     */
    public function handleException($e)
    {

    }

    /**
     * @param  int      $errorType
     * @param  string   $errorMessage
     * @param  string   $errorFile
     * @param  int      $errorLine
     */
    public function handleError(
        $errorType,
        $errorMessage,
        $errorFile,
        $errorLine
    ) {

    }
}
