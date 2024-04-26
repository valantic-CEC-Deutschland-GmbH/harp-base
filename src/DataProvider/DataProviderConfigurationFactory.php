<?php

declare(strict_types = 1);

namespace App\DataProvider;

class DataProviderConfigurationFactory
{

    public function create()
    {
        return DataProviderConfiguration::init();
    }

    public function createProductListDataProvider()
    {
        return DataProviderConfiguration::init('productList');
    }
}
