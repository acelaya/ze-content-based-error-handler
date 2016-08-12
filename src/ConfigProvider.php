<?php
namespace Acelaya\Expressive;

use Acelaya\Expressive\ErrorHandler\ContentBasedErrorHandler;
use Acelaya\Expressive\ErrorHandler\ErrorHandlerManager;
use Acelaya\Expressive\ErrorHandler\Factory\ContentBasedErrorHandlerFactory;
use Acelaya\Expressive\ErrorHandler\Factory\ErrorHandlerManagerFactory;
use Acelaya\Expressive\Log\BasicLogMessageBuilder;
use Acelaya\Expressive\Log\LogMessageBuilderInterface;
use Zend\Expressive\Container\TemplatedErrorHandlerFactory;
use Zend\ServiceManager\Factory\InvokableFactory;
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
                BasicLogMessageBuilder::class => InvokableFactory::class,
            ],
            'aliases' => [
                'Zend\Expressive\FinalHandler' => ContentBasedErrorHandler::class,
                LogMessageBuilderInterface::class => BasicLogMessageBuilder::class,
            ],
        ];
    }

    private function createErrorHandlerConfig()
    {
        return [
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
