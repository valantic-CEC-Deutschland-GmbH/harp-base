<?php

declare(strict_types = 1);

namespace App\DataProvider;

class DataProviderConfigurationFactory
{
    public function createProductListDataProvider()
    {
        return DataProviderConfiguration::init('productList');
    }

    public function createNavigationDataProvider()
    {
        return DataProviderConfiguration::init('navigation');
    }
}
