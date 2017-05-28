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

namespace Xloit\Bridge\Zend\Log\Processor;

/**
 * A {@link MemoryUsage} class will injects memory_get_usage in all records.
 *
 * @package Xloit\Bridge\Zend\Log\Processor
 */
class MemoryUsage extends AbstractMemory
{
    /**
     * Processes a log message before it is given to the writers.
     *
     * @param array $event
     *
     * @return array
     */
    public function process(array $event)
    {
        $bytes     = memory_get_usage($this->realUsage);
        $formatted = $this->formatBytes($bytes);

        $event['extra'] = array_merge(
            $event['extra'],
            ['memory_usage' => $formatted]
        );

        return $event;
    }
}
