<?php

declare(strict_types = 1);

namespace App\DataProvider;

interface DataProviderInterface
{
    /**
     * @param int $id
     *
     * @return string
     */
    public function provide(int $id): string;
}
