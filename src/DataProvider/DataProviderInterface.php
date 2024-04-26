<?php

declare(strict_types = 1);

namespace App\DataProvider;

interface DataProviderInterface
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function provide(int $id): array;
}
