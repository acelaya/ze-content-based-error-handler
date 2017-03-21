<?php
declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler\ErrorHandler\Factory;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\PlainTextResponseGeneratorFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Stratigility\Middleware\ErrorResponseGenerator;

class PlainTextResponseGeneratorFactoryTest extends TestCase
{
    /**
     * @var PlainTextResponseGeneratorFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new PlainTextResponseGeneratorFactory();
    }

    /**
     * @test
     */
    public function serviceIsCreated()
    {
        $instance = $this->factory->__invoke(new ServiceManager([]));
        $this->assertInstanceOf(ErrorResponseGenerator::class, $instance);
    }
}
