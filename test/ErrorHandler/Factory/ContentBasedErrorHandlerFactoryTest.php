<?php
declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler\ErrorHandler\Factory;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorResponseGenerator;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorResponseGeneratorManager;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\ContentBasedErrorResponseGeneratorFactory;
use Acelaya\ExpressiveErrorHandler\Log\LogMessageBuilderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\ServiceManager;

class ContentBasedErrorHandlerFactoryTest extends TestCase
{
    /**
     * @var ContentBasedErrorResponseGeneratorFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ContentBasedErrorResponseGeneratorFactory();
    }

    /**
     * @test
     */
    public function serviceIsCreated()
    {
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            ErrorResponseGeneratorManager::class => $this->prophesize(ErrorResponseGeneratorManager::class)->reveal(),
            LogMessageBuilderInterface::class => $this->prophesize(LogMessageBuilderInterface::class)->reveal(),
        ]]));
        $this->assertInstanceOf(ContentBasedErrorResponseGenerator::class, $instance);

        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            ErrorResponseGeneratorManager::class => $this->prophesize(ErrorResponseGeneratorManager::class)->reveal(),
            LogMessageBuilderInterface::class => $this->prophesize(LogMessageBuilderInterface::class)->reveal(),
            LoggerInterface::class => $this->prophesize(LoggerInterface::class)->reveal(),
        ]]));
        $this->assertInstanceOf(ContentBasedErrorResponseGenerator::class, $instance);
    }

    /**
     * @test
     */
    public function defaultContentTypeIsSetWhenDefined()
    {
        /** @var ContentBasedErrorResponseGenerator $instance */
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            ErrorResponseGeneratorManager::class => $this->prophesize(ErrorResponseGeneratorManager::class)->reveal(),
            LogMessageBuilderInterface::class => $this->prophesize(LogMessageBuilderInterface::class)->reveal(),
            'config' => [
                'error_handler' => [
                    'default_content_type' => 'application/json',
                ],
            ],
        ]]));

        $ref = new \ReflectionObject($instance);
        $prop = $ref->getProperty('defaultContentType');
        $prop->setAccessible(true);
        $this->assertEquals('application/json', $prop->getValue($instance));
    }
}
