<?php

declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler;

use Acelaya\ExpressiveErrorHandler\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    /** @var ConfigProvider */
    protected $configProvider;

    public function setUp(): void
    {
        $this->configProvider = new ConfigProvider();
    }

    /**
     * @test
     */
    public function configIsCorrect(): void
    {
        $config = $this->configProvider->__invoke();
        $this->assertCount(2, $config);
        $this->assertArrayHasKey('dependencies', $config);
        $this->assertArrayHasKey('error_handler', $config);
    }
}
