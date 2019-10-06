<?php

declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\ErrorHandler;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;

use function get_class;
use function gettype;
use function is_callable;
use function is_object;
use function sprintf;

class ErrorResponseGeneratorManager extends AbstractPluginManager implements ErrorResponseGeneratorManagerInterface
{
    /**
     * @param mixed $instance
     * @throws InvalidServiceException
     */
    public function validate($instance)
    {
        if (is_callable($instance)) {
            return;
        }

        throw new InvalidServiceException(sprintf(
            'Only callables are valid plugins for "%s", but "%s" was provided',
            __CLASS__,
            is_object($instance) ? get_class($instance) : gettype($instance)
        ));
    }
}
