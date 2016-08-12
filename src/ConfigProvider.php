<?php
namespace Acelaya\Expressive;

use Acelaya\Expressive\ErrorHandler\ContentBasedErrorHandler;
use Acelaya\Expressive\ErrorHandler\ErrorHandlerManager;
use Acelaya\Expressive\ErrorHandler\Factory\ContentBasedErrorHandlerFactory;
use Acelaya\Expressive\ErrorHandler\Factory\ErrorHandlerManagerFactory;
use Zend\Expressive\Container\TemplatedErrorHandlerFactory;
use Zend\Stratigility\FinalHandler;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->createDependenciesConfig(),
            'error_handler' => $this->createErrorHandlerConfig(),
        ];
    }

    private function createDependenciesConfig()
    {
        return [
            'factories' => [
                ErrorHandlerManager::class => ErrorHandlerManagerFactory::class,
                ContentBasedErrorHandler::class => ContentBasedErrorHandlerFactory::class,
            ],
        ];
    }

    private function createErrorHandlerConfig()
    {
        return [
            'log' => [],
            'plugins' => [
                'invokables' => [
                    'text/plain' => FinalHandler::class,
                ],
                'factories' => [
                    ContentBasedErrorHandler::DEFAULT_CONTENT => TemplatedErrorHandlerFactory::class,
                ],
                'aliases' => [
                    'application/xhtml+xml' => ContentBasedErrorHandler::DEFAULT_CONTENT,
                ],
            ],
        ];
    }
}
