<?php

declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler\ErrorHandler;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorResponseGeneratorManager;
use Closure;
use PHPUnit\Framework\TestCase;
use stdClass;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\ServiceManager;

class ErrorHandlerManagerTest extends TestCase
{
    /** @var ErrorResponseGeneratorManager */
    protected $pluginManager;

    public function setUp(): void
    {
        $this->pluginManager = new ErrorResponseGeneratorManager(new ServiceManager(), [
            'services' => [
                'foo' => function () {
                },
            ],
            'invokables' => [
                'invalid' => stdClass::class,
            ],
        ]);
    }

    /**
     * @test
     */
    public function callablesAreReturned(): void
    {
        $instance = $this->pluginManager->get('foo');
        $this->assertInstanceOf(Closure::class, $instance);
    }

    /**
     * @test
     */
    public function nonCallablesThrowException()
    {
        $this->expectException(InvalidServiceException::class);
        $this->pluginManager->get('invalid');
    }
}
