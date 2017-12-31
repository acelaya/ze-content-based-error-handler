<?php
declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorResponseGenerator;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorResponseGeneratorManager;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\ContentBasedErrorResponseGeneratorFactory;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\ErrorHandlerManagerFactory;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\PlainTextResponseGeneratorFactory;
use Acelaya\ExpressiveErrorHandler\Log\BasicLogMessageBuilder;
use Acelaya\ExpressiveErrorHandler\Log\LogMessageBuilderInterface;
use Zend\Expressive\Container\ErrorResponseGeneratorFactory;
use Zend\Expressive\Middleware\ErrorResponseGenerator;
use Zend\ServiceManager\Factory\InvokableFactory;

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
                ErrorResponseGenerator::class => ContentBasedErrorResponseGenerator::class,
                LogMessageBuilderInterface::class => BasicLogMessageBuilder::class,
            ],
        ];
    }

    private function createErrorHandlerConfig()
    {
        return [
            'default_content_type' => 'text/html',

            'plugins' => [
                'factories' => [
                    'text/plain' => PlainTextResponseGeneratorFactory::class,
                    'text/html' => ErrorResponseGeneratorFactory::class,
                ],
                'aliases' => [
                    'application/xhtml+xml' => 'text/html',
                ],
            ],
        ];
    }
}
