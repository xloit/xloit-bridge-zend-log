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
 * An {@link AbstractMemory} abstract class.
 *
 * @abstract
 * @package Xloit\Bridge\Zend\Log\Processor
 */
abstract class AbstractMemory implements ProcessorInterface
{
    /**
     *
     *
     * @var boolean
     */
    protected $realUsage = true;

    /**
     * Constructor to prevent {@link AbstractMemory} from being loaded more than once.
     *
     * @param boolean $realUsage
     */
    public function __construct($realUsage = true)
    {
        $this->realUsage = (bool) $realUsage;
    }

    /**
     * Formats bytes into a human readable string.
     *
     * @param  int $bytes
     *
     * @return string
     */
    protected function formatBytes($bytes)
    {
        $bytes = (int) $bytes;

        if ($bytes > 1024 * 1024) {
            return round($bytes / 1024 / 1024, 2) . ' MB';
        } elseif ($bytes > 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}
