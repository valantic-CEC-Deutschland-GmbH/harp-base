<?php

declare(strict_types = 1);

use App\DataProvider\DataProviderInterface;

class ProductListDataProvider implements DataProviderInterface
{

    public function provide(int $id): string
    {
        return "";//json_encode(['id' => $id], JSON_THROW_ON_ERROR);
    }
}
