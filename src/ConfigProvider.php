<?php
namespace Acelaya\ExpressiveErrorHandler;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorHandler;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorHandlerManager;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\ContentBasedErrorHandlerFactory;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\ErrorHandlerManagerFactory;
use Acelaya\ExpressiveErrorHandler\Log\BasicLogMessageBuilder;
use Acelaya\ExpressiveErrorHandler\Log\LogMessageBuilderInterface;
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
            'default_content_type' => 'text/html',

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
