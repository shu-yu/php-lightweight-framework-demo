<?php

namespace Libraries;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Common\Errors;

class Log {

    private static $init = false;
    private static $logger;
    private static $logId;
    private static $maxFiles;

    const LOG_CONFIG_FILE = 'log.yaml';

    public static function init($logId, $logName) {

        self::$logId    = $logId;
        self::$maxFiles = 30;

        $output = "[%datetime%][%level_name%]%message%\n";
        $formatter = new LineFormatter($output);

        $logConfig = Configuration::get(self::LOG_CONFIG_FILE);
        if (!is_array($logConfig) || !array_key_exists('logPath', $logConfig) ||
            !is_string($logConfig['logPath'])) {

            Errors::throws(Errors::ERR_CODE_INIT_LOG_ERROR);
            return;
        }

        $stream = new RotatingFileHandler($logConfig['logPath'] . DIRECTORY_SEPARATOR . $logName . '.log', self::$maxFiles, Logger::DEBUG);
        $stream->setFormatter($formatter);

        self::$logger = new Logger($logName);
        self::$logger->pushHandler($stream);

        self::$init = true;
        
    }

    public static function getLogMsg($msg){

        return ('[logId:' . self::$logId . '][' . $msg . ']');

    }

    public static function debug($msg){

        if (!self::$init) {
            return;
        }
        self::$logger->debug(self::getLogMsg($msg));

    }

    public static function info($msg){

        if (!self::$init) {
            return;
        }
        self::$logger->info(self::getLogMsg($msg));

    }

    public static function notice($msg){

        if (!self::$init) {
            return;
        }
        self::$logger->notice(self::getLogMsg($msg));

    }

    public static function warning($msg){

        if (!self::$init) {
            return;
        }
        self::$logger->warning(self::getLogMsg($msg));

    }

    public static function error($msg){

        if (!self::$init) {
            return;
        }
        self::$logger->error(self::getLogMsg($msg));

    }

    public static function critical($msg){

        if (!self::$init) {
            return;
        }
        self::$logger->critical(self::getLogMsg($msg));

    }

    public static function alert($msg){

        if (!self::$init) {
            return;
        }
        self::$logger->alert(self::getLogMsg($msg));

    }

}
