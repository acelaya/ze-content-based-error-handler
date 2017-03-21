<?php
namespace Acelaya\ExpressiveErrorHandler\ErrorHandler;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;

class ErrorResponseGeneratorManager extends AbstractPluginManager implements ErrorResponseGeneratorManagerInterface
{
    public function validate($instance)
    {
        if (is_callable($instance)) {
            return;
        }

        throw new InvalidServiceException(sprintf(
            'Only callables are valid plugins for "%s". "%s" provided',
            __CLASS__,
            is_object($instance) ? get_class($instance) : gettype($instance)
        ));
    }
}
