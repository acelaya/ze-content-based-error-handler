<?php
declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorResponseGenerator;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorResponseGeneratorManager;
use Acelaya\ExpressiveErrorHandler\Log\LogMessageBuilderInterface;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class ContentBasedErrorResponseGeneratorFactory
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
        $config = $container->has('config') ? $container->get('config') : [];
        $ehConfig = isset($config['error_handler']) ? $config['error_handler'] : [];

        $errorHandlerManager = $container->get(ErrorResponseGeneratorManager::class);
        $logger = $container->has(LoggerInterface::class) ? $container->get(LoggerInterface::class) : new NullLogger();
        $logMessageBuilder = $container->get(LogMessageBuilderInterface::class);

        return new ContentBasedErrorResponseGenerator(
            $errorHandlerManager,
            $logger,
            $logMessageBuilder,
            isset($ehConfig['default_content_type']) ? $ehConfig['default_content_type'] : 'text/html'
        );
    }
}
