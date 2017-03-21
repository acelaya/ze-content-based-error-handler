<?php
declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Stratigility\Middleware\ErrorResponseGenerator;

class PlainTextResponseGeneratorFactory
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
        return new ErrorResponseGenerator(isset($config['debug']) ? (bool) $config['debug'] : false);
    }
}
