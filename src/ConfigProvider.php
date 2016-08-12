<?php
namespace Acelaya\Expressive;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->createDependenciesConfig(),
        ];
    }

    private function createDependenciesConfig()
    {
        return [];
    }
}
