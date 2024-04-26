<?php

declare(strict_types = 1);

namespace App\DataProvider;


use App\DataTransformer\DataTransformerInterface;

class DataProviderConfiguration
{
    private function __construct(private array $config)
    {

    }

    public static function init(): DataProviderConfiguration
    {
        $path = __DIR__ . '/../../../config/data-provider-config.json';
        $configArray = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        return new DataProviderConfiguration($configArray);
    }

    public function getEndpoint(): string
    {
        return $this->config['endpoint'];
    }

    public function getDataTransformer(): DataTransformerInterface
    {
        return new $this->config['dataTransformer']();
    }
}
