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
use Xloit\Bridge\Zend\Log\Logger;
use Xloit\Bridge\Zend\ServiceManager\AbstractFactory;
use Zend\Log\Filter\Priority;
use Zend\Log\Writer\Noop;
use Zend\Log\Writer\WriterInterface;

/**
 * A {@link LoggerFactory} class.
 *
 * @package Xloit\Bridge\Zend\Log\Service
 */
class LoggerFactory extends AbstractFactory
{
    /**
     *
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Create the instance service (v3).
     *
     * @param  ContainerInterface $container
     * @param  string             $name
     * @param  null|array         $options
     *
     * @return Logger
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws \Xloit\Std\Exception\RuntimeException
     * @throws \Xloit\Bridge\Zend\ServiceManager\Exception\StateException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Zend\Log\Exception\InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        if ($this->hasOption('errorHandler')
            && $this->getOption('errorHandler', false)
        ) {
            Logger::registerErrorHandler($this->getLogger());
        }

        if ($this->hasOption('exceptionHandler')
            && $this->getOption('exceptionHandler', false)
        ) {
            Logger::registerExceptionHandler($this->getLogger());
        }

        if ($this->hasOption('fatalErrorHandler')
            && $this->getOption('fatalErrorHandler', false)
        ) {
            Logger::registerFatalErrorShutdownFunction($this->getLogger());
        }

        if ($this->hasOption('writers')) {
            $this->addWriters($this->getOption('writers', false));
        }

        if ($this->getLogger()->getWriters()->count() === 0) {
            return $this->getLogger()->addWriter(new Noop());
        }

        return $this->getLogger();
    }

    /**
     *
     *
     * @return string
     */
    public function getInstanceClass()
    {
        return Logger::class;
    }

    /**
     *
     *
     * @return Logger
     * @throws \Zend\Log\Exception\InvalidArgumentException
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $this->logger = new Logger();
        }

        return $this->logger;
    }

    /**
     *
     *
     * @param array $writers
     *
     * @return void
     * @throws \Zend\Log\Exception\InvalidArgumentException
     */
    protected function addWriters(array $writers)
    {
        foreach ($writers as $writer) {
            if (array_key_exists('enable', $writer) && !$writer['enable']) {
                continue;
            }

            unset($writer['enable']);

            $writerAdapter = $this->getWriterAdapter($writer);

            $this->getLogger()->addWriter($writerAdapter);
        }
    }

    /**
     *
     *
     * @param array $writer
     *
     * @return WriterInterface
     * @throws \Zend\Log\Exception\InvalidArgumentException
     */
    protected function getWriterAdapter(array $writer)
    {
        $writerClass = $writer['adapter'];

        /** @var $writerAdapter \Zend\Log\Writer\WriterInterface */
        $writerAdapter = new $writerClass($writer['options']['output']);

        $writerAdapter->addFilter(new Priority($writer['filter']));

        return $writerAdapter;
    }
}
