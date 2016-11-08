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
use Zend\Log\LoggerInterface as ZendLoggerInterface;

/**
 * A {@link LoggerInterface} interface.
 *
 * @package Xloit\Bridge\Zend\Log
 */
interface LoggerInterface extends ZendLoggerInterface
{
    /**
     * Add a message as a log entry
     *
     * @param int                $priority
     * @param mixed              $message
     * @param array|\Traversable $extra
     *
     * @return static
     */
    public function log($priority, $message, $extra = []);

    /**
     * Log an exception triggered by ZF2 for administrative purposes.
     *
     * @param Throwable          $error
     * @param array|\Traversable $extra
     *
     * @return static
     */
    public function exception(Throwable $error, $extra = []);
}