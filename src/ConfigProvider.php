<?php
namespace Acelaya\Expressive;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->createDependenciesConfig(),
            'error_handler' => $this->createErrorHandlerConfig(),
        ];
    }

    private function createDependenciesConfig()
    {
        return [

        ];
    }

    private function createErrorHandlerConfig()
    {
        return [
            'plugins' => [],
        ];
    }
}
