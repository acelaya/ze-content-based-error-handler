<?php
namespace Acelaya\ExpressiveErrorHandler;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorResponseGenerator;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorResponseGeneratorManager;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\ContentBasedErrorResponseGeneratorFactory;
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
                ErrorResponseGeneratorManager::class => ErrorHandlerManagerFactory::class,
                ContentBasedErrorResponseGenerator::class => ContentBasedErrorResponseGeneratorFactory::class,
                BasicLogMessageBuilder::class => InvokableFactory::class,
            ],
            'aliases' => [
                'Zend\Expressive\FinalHandler' => ContentBasedErrorResponseGenerator::class,
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
                    'text/html' => TemplatedErrorHandlerFactory::class,
                ],
                'aliases' => [
                    'application/xhtml+xml' => 'text/html',
                ],
            ],
        ];
    }
}
