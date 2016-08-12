<?php
namespace AcelayaTest\Expressive;

use Acelaya\Expressive\ConfigProvider;
use PHPUnit_Framework_TestCase as TestCase;

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
