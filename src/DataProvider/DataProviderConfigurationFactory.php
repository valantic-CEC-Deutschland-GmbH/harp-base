<?php

declare(strict_types = 1);

namespace App\DataProvider;

class DataProviderConfigurationFactory
{

    public function create()
    {
        return DataProviderConfiguration::init();
    }

    public function createProductListDataProvider(): DataProviderConfigurationInterface
    {
        return DataProviderConfiguration::init('productList');
    }
}
