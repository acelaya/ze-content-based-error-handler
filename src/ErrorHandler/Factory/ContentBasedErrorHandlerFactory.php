<?php
namespace Acelaya\Expressive\ErrorHandler\Factory;

use Acelaya\Expressive\ErrorHandler\ContentBasedErrorHandler;
use Acelaya\Expressive\ErrorHandler\ErrorHandlerManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
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
        return new ContentBasedErrorHandler($errorHandlerManager);
    }
}
