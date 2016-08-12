<?php
namespace AcelayaTest\Expressive\ErrorHandler\Factory;

use Acelaya\Expressive\ErrorHandler\ContentBasedErrorHandler;
use Acelaya\Expressive\ErrorHandler\ErrorHandlerManager;
use Acelaya\Expressive\ErrorHandler\Factory\ContentBasedErrorHandlerFactory;
use Acelaya\Expressive\Log\LogMessageBuilderInterface;
use PHPUnit_Framework_TestCase as TestCase;
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
}
