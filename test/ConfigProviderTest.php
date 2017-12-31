<?php
declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler;

use Acelaya\ExpressiveErrorHandler\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    /**
     * @var ConfigProvider
     */
    protected $configProvider;

    public function setUp()
    {
        $this->configProvider = new ConfigProvider();
    }

    /**
     * @test
     */
    public function configIsCorrect()
    {
        $config = $this->configProvider->__invoke();
        $this->assertArrayHasKey('dependencies', $config);
        $this->assertArrayHasKey('error_handler', $config);
    }
}
