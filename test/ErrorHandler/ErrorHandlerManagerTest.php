<?php
declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler\ErrorHandler;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorResponseGeneratorManager;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;

class ErrorHandlerManagerTest extends TestCase
{
    /**
     * @var ErrorResponseGeneratorManager
     */
    protected $pluginManager;

    public function setUp()
    {
        $this->pluginManager = new ErrorResponseGeneratorManager(new ServiceManager(), [
            'services' => [
                'foo' => function () {
                },
            ],
            'invokables' => [
                'invalid' => \stdClass::class,
            ],
        ]);
    }

    /**
     * @test
     */
    public function callablesAreReturned()
    {
        $instance = $this->pluginManager->get('foo');
        $this->assertInstanceOf(\Closure::class, $instance);
    }

    /**
     * @test
     * @expectedException \Zend\ServiceManager\Exception\InvalidServiceException
     */
    public function nonCallablesThrowException()
    {
        $this->pluginManager->get('invalid');
    }
}
