<?php
namespace AcelayaTest\Expressive\ErrorHandler\Factory;

use Acelaya\Expressive\ErrorHandler\ErrorHandlerManager;
use Acelaya\Expressive\ErrorHandler\Factory\ErrorHandlerManagerFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

class ErrorHandlerManagerFactoryTest extends TestCase
{
    /**
     * @var ErrorHandlerManagerFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ErrorHandlerManagerFactory();
    }

    /**
     * @test
     */
    public function serviceIsCreated()
    {
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            'config' => [
                'error_handler' => [
                    'plugins' => [],
                ],
            ],
        ]]));
        $this->assertInstanceOf(ErrorHandlerManager::class, $instance);
    }
}
