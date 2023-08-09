<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

ini_set("date.timezone", "Africa/Nairobi");

/**
 * This class contains the logging utility functions.
 * @category  Logging
 * @package   Logger
 * @author    Josephat Mukuha <josephat@southwell.io>
 * @copyright 2016 SouthWell Solutions Africa
 * @license   Proprietory License
 * @link      http://www.southwell.io
 */
class MenuLogger {

    protected $dateFormat = 'Y-m-d H:i:s';
	protected $output = "%datetime% - SPOTIKA CRON JOBS - [%level_name%] - %message%\n";
    protected $formatter = null;
    protected $ipaddress = null;
    protected $PRODUCTION = TRUE;
    protected $infoLogs;
    protected $errorLogs;
    public $config;

    function __construct($config) {

		$infoLogs = $config->info;
		$errorLogs = $config->error;

		$this->formatter = new LineFormatter($this->output, 'Y-m-d H:i:s');
		$this->infoLogs = $infoLogs;
		$this->errorLogs = $errorLogs;
    }

    /**
     * writes the info log
     */
    public function INFO($message) {
        $streamFile =  $this->infoLogs;
        $stream = new StreamHandler($streamFile, Logger::INFO);
        $stream->setFormatter($this->formatter);
        $infoLogger = new Logger('INFO');
        $infoLogger->pushHandler($stream);
        $infoLogger->addInfo($message);
    }

    /**
     * writes the error log
     */
    public function ERROR($message) {
        $streamFile =  $this->errorLogs;
        $stream = new StreamHandler($streamFile, Logger::ERROR);
        $stream->setFormatter($this->formatter);
        $infoLogger = new Logger('ERROR');
        $infoLogger->pushHandler($stream);
        $infoLogger->addError($message);
    }

    /**
     * writes the debug log
     */
    public function DEBUG($message) {
        $streamFile =  $this->infoLogs;
        $stream = new StreamHandler($streamFile, Logger::DEBUG);
        $stream->setFormatter($this->formatter);
        $infoLogger = new Logger('DEBUG');
        $infoLogger->pushHandler($stream);
        $infoLogger->addDebug($message);
    }

    /**
     * writes the alert log
     */
    public function ALERT($message) {
        $streamFile =  $this->infoLogs;
        $stream = new StreamHandler($streamFile, Logger::ALERT);
        $stream->setFormatter($this->formatter);
        $infoLogger = new Logger('ALERT');
        $infoLogger->pushHandler($stream);
        $infoLogger->addAlert("localhost-" . $message);
    }

    /**
     * writes the Memory usage log
     */
    public function EXCEPTION($message) {
        $streamFile =  $this->errorLogs;
        $stream = new StreamHandler($streamFile, Logger::CRITICAL);
        $stream->setFormatter($this->formatter);
        $infoLogger = new Logger('EXCEPTION');
        $infoLogger->pushHandler($stream);
        $infoLogger->addEmergency($message);
    }

    /**
     * writes the security log
     */
    public function SECURITY($message) {
        $streamFile =  $this->infoLogs;
        $stream = new StreamHandler($streamFile, Logger::EMERGENCY);
        $stream->setFormatter($this->formatter);
        $infoLogger = new Logger('SECURITY');
        $infoLogger->pushHandler($stream);
        $infoLogger->addEmergency("localhost-" . $message);
    }

}
