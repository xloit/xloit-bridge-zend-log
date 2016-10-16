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
 * A {@link RemoteAddress} class.
 *
 * @package Xloit\Bridge\Zend\Log\Processor
 */
class RemoteAddress implements ProcessorInterface
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
        $remoteAddress = null;

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $remoteAddress = $_SERVER['HTTP_CLIENT_IP'];
        } /** @noinspection UnSafeIsSetOverArrayInspection */
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $remoteAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } /** @noinspection UnSafeIsSetOverArrayInspection */
        elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
            $remoteAddress = $_SERVER['REMOTE_ADDR'];
        }

        if (!array_key_exists('extra', $event)) {
            $event['extra'] = [];
        }

        $event['extra']['ip'] = $remoteAddress;

        return $event;
    }
}
