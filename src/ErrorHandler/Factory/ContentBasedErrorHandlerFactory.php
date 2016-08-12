<?php
namespace Acelaya\Expressive\ErrorHandler\Factory;

use Acelaya\Expressive\ErrorHandler\ContentBasedErrorHandler;
use Acelaya\Expressive\ErrorHandler\ErrorHandlerManager;
use Acelaya\Expressive\Log\LogMessageBuilderInterface;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class ContentBasedErrorHandlerFactory
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container)
    {
        $errorHandlerManager = $container->get(ErrorHandlerManager::class);
        $logMessageBuilder = $container->get(LogMessageBuilderInterface::class);
        $logger = $container->has(LoggerInterface::class) ? $container->get(LoggerInterface::class) : new NullLogger();

        return new ContentBasedErrorHandler($errorHandlerManager, $logMessageBuilder, $logger);
    }
}
