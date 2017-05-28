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

use Traversable;
use Xloit\Bridge\Zend\Log\Exception;
use Zend\Log\Processor\ProcessorInterface;

/**
 * A {@link Web} class.
 *
 * @package Xloit\Bridge\Zend\Log\Processor
 */
class Web implements ProcessorInterface
{
    /**
     *
     *
     * @var Traversable|array
     */
    protected $serverData;

    /**
     * Constructor to prevent {@link Web} from being loaded more than once.
     *
     * @param Traversable|array $serverData An array or Traversable that provides access to the $_SERVER data.
     *
     * @throws \Xloit\Bridge\Zend\Log\Exception\UnexpectedValueException
     */
    public function __construct($serverData = null)
    {
        if (!is_array($serverData) || !($serverData instanceof Traversable)) {
            throw new Exception\UnexpectedValueException(
                '$serverData must be an array or object implementing ArrayAccess.'
            );
        }

        $this->setServerData($serverData);
    }

    /**
     *
     *
     * @return Traversable|array
     */
    public function getServerData()
    {
        return $this->serverData;
    }

    /**
     *
     *
     * @param Traversable|array $serverData
     *
     * @return $this
     */
    public function setServerData($serverData)
    {
        $this->serverData = $serverData;

        return $this;
    }

    /**
     * Processes a log message before it is given to the writers.
     *
     * @param array $event
     *
     * @return array
     */
    public function process(array $event)
    {
        $serverData = $this->getServerData();

        /** skip processing if for some reason request data is not present (CLI or wonky SAPIs) */
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($serverData['REQUEST_URI'])) {
            return $event;
        }

        $extras = [];

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($serverData['REQUEST_METHOD'])) {
            $extras['http_method'] = $serverData['REQUEST_METHOD'];
        }

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($serverData['SERVER_NAME'])) {
            $extras['server'] = $serverData['SERVER_NAME'];
        }

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($serverData['HTTP_REFERER'])) {
            $extras['referrer'] = $serverData['HTTP_REFERER'];
        }

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($serverData['REMOTE_ADDR'])) {
            $extras['ip'] = $serverData['REMOTE_ADDR'];
        }

        /** @noinspection SpellCheckingInspection */
        $event['extra'] = array_merge($event['extra'], $extras);

        return $event;
    }
}
