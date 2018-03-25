<?php
declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\ErrorHandler;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;

class ErrorResponseGeneratorManager extends AbstractPluginManager implements ErrorResponseGeneratorManagerInterface
{
    /**
     * @param $instance
     * @throws InvalidServiceException
     */
    public function validate($instance)
    {
        if (\is_callable($instance)) {
            return;
        }

        throw new InvalidServiceException(\sprintf(
            'Only callables are valid plugins for "%s", but "%s" was provided',
            __CLASS__,
            \is_object($instance) ? \get_class($instance) : \gettype($instance)
        ));
    }
}
