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

use Exception;
use Zend\Log\Formatter\Base;

/**
 * An {@link AbstractFormatter} abstract class will normalizes incoming records to remove resources so it's easier
 * to dump to various targets.
 *
 * @abstract
 * @package Xloit\Bridge\Zend\Log\Formatter
 */
abstract class AbstractFormatter extends Base implements FormatterInterface
{
    /**
     *
     *
     * @param Exception $e
     *
     * @return array
     */
    protected function normalizeException(Exception $e)
    {
        $event = [
            'class'   => get_class($e),
            'message' => $e->getMessage(),
            'file'    => $e->getFile() . ':' . $e->getLine()
        ];

        $trace = $e->getTrace();

        array_shift($trace);

        foreach ($trace as $frame) {
            /** @noinspection UnSafeIsSetOverArrayInspection */
            if (isset($frame['file'])) {
                $event['trace'][] = $frame['file'] . ':' . $frame['line'];
            } else {
                $event['trace'][] = $frame;
            }
        }

        $previous = $e->getPrevious();

        if ($previous) {
            $event['previous'] = $this->normalizeException($previous);
        }

        return $this->format($event);
    }

    /**
     * Formats data to be written by the writer.
     *
     * @param array $event event data
     *
     * @return array
     */
    public function format($event)
    {
        $results = [];

        foreach ($event as $key => $value) {
            if ('extra' === $key && is_array($value)) {
                $results[$key] = $this->format($value);
            } else {
                $results[$key] = $this->normalize($value);
            }
        }

        return $results;
    }

    /**
     *
     *
     * @param mixed $data
     *
     * @return string
     */
    protected function toJson($data)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return json_encode($data);
    }
}
