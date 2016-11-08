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

use Throwable;
use Zend\Log\Logger as ZendLogger;

/**
 * A {@link Logger} class.
 *
 * @package Xloit\Bridge\Zend\Log
 */
class Logger extends ZendLogger implements LoggerInterface
{
    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string             $message
     * @param array|\Traversable $extra
     *
     * @return static
     * @throws \Zend\Log\Exception\InvalidArgumentException
     * @throws \Zend\Log\Exception\InvalidArgumentException
     * @throws \Zend\Log\Exception\RuntimeException
     */
    public function err($message, $extra = [])
    {
        if ($message instanceof Throwable) {
            return $this->exception($message, $extra);
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::err($message, $extra);
    }

    /**
     * Log an exception triggered by ZF2 for administrative purposes.
     *
     * @param Throwable          $error
     * @param array|\Traversable $extra
     *
     * @return static
     * @throws \Zend\Log\Exception\InvalidArgumentException
     * @throws \Zend\Log\Exception\InvalidArgumentException
     * @throws \Zend\Log\Exception\RuntimeException
     */
    public function exception(Throwable $error, $extra = [])
    {
        // We need to build a variety of pieces so we can supply information at five different verbosity levels:
        $messages   = $error->getMessage();
        $traces     = $error->getTrace();
        $serverInfo = $this->getServerInfo();
        $backtrace  = [];

        if (is_array($traces)) {
            foreach ($traces as $line) {
                /** @noinspection UnSafeIsSetOverArrayInspection */
                if (!isset($line['file'])) {
                    $line['file'] = 'unlisted file';
                }

                /** @noinspection UnSafeIsSetOverArrayInspection */
                if (!isset($line['line'])) {
                    $line['line'] = 'unlisted';
                }

                $backtrace[] = $line['file'];
                $backtrace[] = 'line::' . $line['line'];

                /** @noinspection UnSafeIsSetOverArrayInspection */
                if (isset($line['class'])) {
                    $backtrace[] = sprintf('%s::%s', $line['class'], $line['function']);
                }

                if (!empty($line['args'])) {
                    /** @var array $lineArgs */
                    $lineArgs = $line['args'];
                    $args     = [];

                    foreach ($lineArgs as $i => $arg) {
                        $args[] = $i . ' ->' . $this->typeToString($arg);
                    }

                    $backtrace[] = 'Args::' . implode(', ', $args);
                } else {
                    $backtrace[] = 'Args::None';
                }

                $backtrace[] = '';
            }
        }

        $backtrace = implode("\n", $backtrace);

        $errorDetails = [
            1 => $messages,
            2 => $messages . $serverInfo,
            3 => $messages . $serverInfo . $backtrace
        ];

        $this->log(self::ERR, $errorDetails, $extra);

        return $this;
    }

    /**
     *
     *
     * @return string
     */
    protected function getServerInfo()
    {
        $serverInfo = [
            'HOST'         => !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CLI',
            'HTTP_REFERER' => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'None',
            'IP'           => !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unavailable',
            'USER_AGENT'   => !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown',
            'REQUEST_URI'  => $_SERVER['REQUEST_URI']
        ];
        $messages   = [];

        foreach ($serverInfo as $name => $info) {
            $messages[] = sprintf('%s => %s', $name, $info);
        }

        return sprintf('(Server Info :: %s)', implode(', ', $messages));
    }

    /**
     * Convert type data to a string.
     *
     * @param mixed $type
     *
     * @return string
     */
    protected function typeToString($type)
    {

        if (is_object($type)) {
            return sprintf('Object::%s', get_class($type));
        }

        if (is_array($type)) {
            $types = [];

            /** @noinspection ForeachSourceInspection */
            foreach ($type as $key => $item) {
                $types[] = sprintf('%s => %s', $key, $this->typeToString($item));
            }

            return 'Array(' . implode(', ', $types) . ')';
        }

        if (is_bool($type)) {
            return sprintf('Boolean::%s', $type ? 'True' : 'False');
        }

        if (is_int($type) || is_float($type)) {
            return sprintf('Integer::%s', (string) $type);
        }

        if ($type === null) {
            return 'null';
        }

        return "'$type'";
    }
}
