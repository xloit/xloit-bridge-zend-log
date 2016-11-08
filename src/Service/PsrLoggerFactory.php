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
namespace Xloit\Bridge\Zend\Log\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Xloit\Bridge\Zend\Log\PsrLogger;
use Xloit\Bridge\Zend\ServiceManager\AbstractFactory;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * A {@link PsrLoggerFactory} class.
 *
 * @package Xloit\Bridge\Zend\Log\Service
 */
class PsrLoggerFactory extends AbstractFactory
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return PsrLogger
     * @throws \Xloit\Std\Exception\RuntimeException
     * @throws \Xloit\Bridge\Zend\ServiceManager\Exception\StateException
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PsrLogger($this->getOption('logger'));
    }
}
