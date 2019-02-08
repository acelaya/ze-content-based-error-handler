<?php
declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler\ErrorHandler\Factory;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\PlainTextResponseGeneratorFactory;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use Zend\ServiceManager\ServiceManager;
use Zend\Stratigility\Middleware\ErrorResponseGenerator;

class PlainTextResponseGeneratorFactoryTest extends TestCase
{
    /** @var PlainTextResponseGeneratorFactory */
    protected $factory;

    public function setUp(): void
    {
        $this->factory = new PlainTextResponseGeneratorFactory();
    }

    /**
     * @test
     * @dataProvider provideDebugs
     */
    public function serviceIsCreated(array $config, bool $expectedIsDev): void
    {
        $instance = $this->factory->__invoke(new ServiceManager(['services' => [
            'config' => $config,
        ]]));

        $ref = new ReflectionObject($instance);
        $isDev = $ref->getProperty('isDevelopmentMode');
        $isDev->setAccessible(true);

        $this->assertInstanceOf(ErrorResponseGenerator::class, $instance);
        $this->assertEquals($expectedIsDev, $isDev->getValue($instance));
    }

    public function provideDebugs(): array
    {
        return [
            [[], false],
            [['debug' => true], true],
            [['debug' => false], false],
        ];
    }
}
