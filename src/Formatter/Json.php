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

/**
 * A {@link Json} class encodes whatever event data is passed to it as json. This can be useful to log to databases
 * or remote APIs.
 *
 * @package Xloit\Bridge\Zend\Log\Formatter
 */
class Json extends AbstractFormatter
{
    /**
     * Formats data to be written by the writer.
     *
     * @param array $event An event data.
     *
     * @return string
     */
    public function format($event)
    {
        return $this->toJson($event);
    }
}
