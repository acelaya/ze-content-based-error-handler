<?php
namespace Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorResponseGeneratorManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class ErrorHandlerManagerFactory
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
        $errorHandlerConfig = isset($config['error_handler']) ? $config['error_handler'] : [];
        $plugins = isset($errorHandlerConfig['plugins']) ? $errorHandlerConfig['plugins'] : [];
        return new ErrorResponseGeneratorManager($container, $plugins);
    }
}
