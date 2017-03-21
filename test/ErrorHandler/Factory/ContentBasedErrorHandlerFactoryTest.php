<?php
namespace AcelayaTest\ExpressiveErrorHandler\ErrorHandler\Factory;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorHandler;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorHandlerManager;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\ContentBasedErrorHandlerFactory;
use Acelaya\ExpressiveErrorHandler\Log\LogMessageBuilderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\ServiceManager;

class ContentBasedErrorHandlerFactoryTest extends TestCase
{
    /**
     * @var ContentBasedErrorHandlerFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ContentBasedErrorHandlerFactory();
    }

    /**
     * @test
     */
    public function serviceIsCreated()
    {
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            ErrorHandlerManager::class => $this->prophesize(ErrorHandlerManager::class)->reveal(),
            LogMessageBuilderInterface::class => $this->prophesize(LogMessageBuilderInterface::class)->reveal(),
        ]]));
        $this->assertInstanceOf(ContentBasedErrorHandler::class, $instance);

        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            ErrorHandlerManager::class => $this->prophesize(ErrorHandlerManager::class)->reveal(),
            LogMessageBuilderInterface::class => $this->prophesize(LogMessageBuilderInterface::class)->reveal(),
            LoggerInterface::class => $this->prophesize(LoggerInterface::class)->reveal(),
        ]]));
        $this->assertInstanceOf(ContentBasedErrorHandler::class, $instance);
    }

    /**
     * @test
     */
    public function defaultContentTypeIsSetWhenDefined()
    {
        /** @var ContentBasedErrorHandler $instance */
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            ErrorHandlerManager::class => $this->prophesize(ErrorHandlerManager::class)->reveal(),
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
