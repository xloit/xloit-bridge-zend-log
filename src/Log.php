<?php
/**
 * This source file is part of Xloit project.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * <http://www.opensource.org/licenses/mit-license.php>
 * If you did not receive a copy of the license and are unable to obtain it through the world-wide-web,
 * please send an email to <license@xloit.com> so we can send you a copy immediately.
 *
 * @license   MIT
 * @link      http://xloit.com
 * @copyright Copyright (c) 2016, Xloit. All rights reserved.
 */

namespace Xloit\Bridge\Zend\Log;

use Exception as PhpException;
use Zend\Log\Writer;

/**
 * A {@link Log} class.
 *
 * @package Xloit\Bridge\Zend\Log
 */
class Log
{
    /**
     *
     *
     * @var boolean
     */
    protected static $registeredExceptionHandler = false;

    /**
     *
     *
     * @var Logger
     */
    private static $logger;

    /**
     *
     *
     * @param Logger $log
     * @param string $logVerbosity
     *
     * @return void
     * @throws Exception\UnexpectedValueException
     */
    public static function init(Logger $log, $logVerbosity = 'NOTICE')
    {
        self::clean();

        switch (strtoupper($logVerbosity)) {
            case 'EMERG':
                $verbosity = Logger::EMERG;
                break;
            case 'ALERT':
                $verbosity = Logger::ALERT;
                break;
            case 'ERR':
                $verbosity = Logger::ERR;
                break;
            case 'WARN':
                $verbosity = Logger::WARN;
                break;
            case 'CRIT':
                $verbosity = Logger::CRIT;
                break;
            case 'NOTICE':
                $verbosity = Logger::NOTICE;
                break;
            case 'INFO':
                $verbosity = Logger::INFO;
                break;
            case 'DEBUG':
                $verbosity = Logger::DEBUG;
                break;
            default:
                throw new Exception\UnexpectedValueException("Incorrect logVerbosity setting: {$logVerbosity}");
        }

        $writers = $log->getWriters()->toArray();

        foreach ($writers as $writer) {
            /* @var $writer Writer\WriterInterface */
            $writer->addFilter($verbosity);
        }

        static::$logger = $log;
    }

    /**
     *
     *
     * @return void
     */
    public static function clean()
    {
        static::$logger = null;
    }

    /**
     *
     *
     * @return Logger
     */
    public static function getLogger()
    {
        return static::$logger;
    }

    /**
     * Save an exception as an error in the log file.
     *
     * @param string       $title
     * @param PhpException $e
     *
     * @return boolean
     * @throws \Zend\Log\Exception\InvalidArgumentException
     * @throws \Zend\Log\Exception\InvalidArgumentException
     * @throws \Zend\Log\Exception\RuntimeException
     */
    public static function exception($title, PhpException $e)
    {
        $exceptionMessage = (string) $e->getMessage();

        $content = (string) $title . PHP_EOL . 'Exception of type \'' . get_class($e) . '\': ' . $exceptionMessage;

        // need to add check if the log verbosity is info or debug. Look at old code
        self::$logger->exception(new PhpException($content, null, $e));

        return true;
    }

    /**
     * Log an error message for external consumption.
     *
     * @param string $message
     * @param array  $extras
     * @throws \Zend\Log\Exception\InvalidArgumentException
     * @throws \Zend\Log\Exception\InvalidArgumentException
     * @throws \Zend\Log\Exception\RuntimeException
     */
    public static function err($message, array $extras = [])
    {
        if (self::$logger) {
            self::$logger->err($message, $extras);
        }
    }

    /**
     * Log a debug message for internal use.
     *
     * @param string $message
     * @param array  $extras
     *
     * @return void
     */
    public static function debug($message, array $extras = [])
    {
        if (self::$logger) {
            self::$logger->debug($message, $extras);
        }
    }

    /**
     * Log an info message for external consumption.
     *
     * @param string $message
     * @param array  $extras
     *
     * @return void
     */
    public static function info($message, array $extras = [])
    {
        if (self::$logger) {
            self::$logger->info($message, $extras);
        }
    }

    /**
     * Log a notice message for external consumption.
     *
     * @param string $message
     * @param array  $extras
     *
     * @return void
     */
    public static function notice($message, array $extras = [])
    {
        if (self::$logger) {
            self::$logger->notice($message, $extras);
        }
    }

    /**
     * Log a warning message for external consumption.
     *
     * @param string $message
     * @param array  $extras
     *
     * @return void
     */
    public static function warn($message, array $extras = [])
    {
        if (self::$logger) {
            self::$logger->warn($message, $extras);
        }
    }

    /**
     * Log a critical message for external consumption.
     *
     * @param string $message
     * @param array  $extras
     *
     * @return void
     */
    public static function crit($message, array $extras = [])
    {
        if (self::$logger) {
            self::$logger->crit($message, $extras);
        }
    }

    /**
     * Log an alert message for external consumption.
     *
     * @param string $message
     * @param array  $extras
     *
     * @return void
     */
    public static function alert($message, array $extras = [])
    {
        if (self::$logger) {
            self::$logger->alert($message, $extras);
        }
    }

    /**
     * Log an emergency message for external consumption.
     *
     * @param string $message
     * @param array  $extras
     *
     * @return void
     */
    public static function emerg($message, array $extras = [])
    {
        if (self::$logger) {
            self::$logger->emerg($message, $extras);
        }
    }

    /**
     *
     *
     * @param Logger $logger
     *
     * @return boolean
     * @throws \Zend\Log\Exception\RuntimeException
     * @throws \Zend\Log\Exception\InvalidArgumentException
     */
    public static function registerExceptionHandler(Logger $logger = null)
    {
        $logger = $logger ?: static::$logger;

        // Only register once per instance
        if (self::$registeredExceptionHandler) {
            return false;
        }

        set_exception_handler(
            function($exception) use ($logger) {
                $logger->exception($exception);
            }
        );

        self::$registeredExceptionHandler = true;

        return true;
    }
}
