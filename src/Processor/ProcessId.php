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

use Zend\Log\Processor\ProcessorInterface;

/**
 * A {@link ProcessId} class will adds value of getmypid into records.
 *
 * @package Xloit\Bridge\Zend\Log\Processor
 */
class ProcessId implements ProcessorInterface
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
        $event['extra']['process_id'] = getmypid();

        return $event;
    }
}
