<?php

declare(strict_types = 1);

namespace App\DataProvider;

interface DataProviderInterface
{
    /**
     * @param string|int $id
     *
     * @return array
     */
    public function provide(string|int $id): array;
}
