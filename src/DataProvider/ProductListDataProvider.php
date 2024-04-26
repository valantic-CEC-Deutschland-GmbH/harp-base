<?php

declare(strict_types = 1);

namespace App\DataProvider;


class ProductListDataProvider implements DataProviderInterface
{
    public function __construct(private DataProviderConfigurationFactory $factory)
    {}

    public function provide(int $id): string
    {
        return "";//json_encode(['id' => $id], JSON_THROW_ON_ERROR);
    }
}
