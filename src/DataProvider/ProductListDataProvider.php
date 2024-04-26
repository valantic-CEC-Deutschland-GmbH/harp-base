<?php

declare(strict_types = 1);

namespace App\DataProvider;


class ProductListDataProvider implements DataProviderInterface
{
    public function __construct(private DataProviderConfigurationFactory $factory)
    {}

    public function provide(int $id): string
    {
        $providerConfig = $this->factory->createProductListDataProvider();

        $endpoint = $providerConfig->getEndpoint();

        $transformer = $providerConfig->getDataTransformer();

        $inputParams = $transformer

        (new GuzzleClient())->sendRequest(Request ::setConfig($this->factory->create());
        return "";//json_encode(['id' => $id], JSON_THROW_ON_ERROR);
    }
}
