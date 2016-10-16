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

namespace Xloit\Bridge\Zend\Log\Formatter;

use Zend\Log\Logger;

/**
 * A {@link Wildfire} class will serializes a log message according to Wildfire's header requirements.
 *
 * @package Xloit\Bridge\Zend\Log\Formatter
 */
class Wildfire extends AbstractFormatter
{
    /**
     * List of priority code => priority (short) name.
     *
     * @var array
     */
    protected $priorities = [
        Logger::EMERG  => 'ERROR',
        Logger::ALERT  => 'ERROR',
        Logger::CRIT   => 'ERROR',
        Logger::ERR    => 'ERR',
        Logger::WARN   => 'WARN',
        Logger::NOTICE => 'INFO',
        Logger::INFO   => 'INFO',
        Logger::DEBUG  => 'LOG'
    ];

    /**
     * Formats data to be written by the writer.
     *
     * @param array $event event data
     *
     * @return array
     */
    public function format($event)
    {
        /** Retrieve the line and file if set and remove them from the formatted extra */
        $file       = $line = '';
        $priorities = 0;

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($event['extra']['file'])) {
            $file = $event['extra']['file'];

            unset($event['extra']['file']);
        }

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($event['extra']['line'])) {
            $line = $event['extra']['line'];

            unset($event['extra']['line']);
        }

        $event = $this->normalize($event);

        $message = ['message' => $event['message']];

        if ($event['context']) {
            $message['context'] = $event['context'];
        }

        if ($event['extra']) {
            $message['extra'] = $event['extra'];
        }

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($this->priorities[$event['level']])) {
            $priorities = $this->priorities[$event['level']];
        }

        if (count($message) === 1) {
            $message = reset($message);
        }

        /** Create JSON object describing the appearance of the message in the console */
        $json = $this->toJson(
            [
                [
                    'Type'  => $priorities,
                    'File'  => $file,
                    'Line'  => $line,
                    'Label' => $event['channel']
                ],
                $message
            ]
        );

        return sprintf('%s|%s|', strlen($json), $json);
    }
}
