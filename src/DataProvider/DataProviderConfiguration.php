<?php

declare(strict_types = 1);

namespace App\DataProvider;


use App\DataTransformer\DataTransformerInterface;

class DataProviderConfiguration
{
    private function __construct(private array $config)
    {
    }

    /**
     * @param string $configName
     *
     * @return \App\DataProvider\DataProviderConfiguration
     */
    public static function init(string $configName): DataProviderConfiguration
    {
        $path = __DIR__ . '/../../config/data/' . $configName . '-config.json';

        var_dump($path);
        $content = file_get_contents($path);

        var_dump($content);
        $configArray = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new DataProviderConfiguration($configArray);
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->config['endPoint'];
    }

    /**
     * @return \App\DataTransformer\DataTransformerInterface
     */
    public function getDataTransformer(): DataTransformerInterface
    {
        return new $this->config['dataTransformer']();
    }
}
