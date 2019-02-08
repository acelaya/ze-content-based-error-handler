<?php
declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler\ErrorHandler\Factory;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorResponseGeneratorManager;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\ErrorHandlerManagerFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;

class ErrorHandlerManagerFactoryTest extends TestCase
{
    /** @var ErrorHandlerManagerFactory */
    protected $factory;

    public function setUp(): void
    {
        $this->factory = new ErrorHandlerManagerFactory();
    }

    /**
     * @test
     */
    public function serviceIsCreated(): void
    {
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            'config' => [
                'error_handler' => [
                    'plugins' => [],
                ],
            ],
        ]]));
        $this->assertInstanceOf(ErrorResponseGeneratorManager::class, $instance);
    }
}
