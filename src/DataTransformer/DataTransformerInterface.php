<?php

declare(strict_types = 1);

namespace App\DataTransformer;

interface DataTransformerInterface
{
    public function transformInputParameters(array $params): array;

    public function transformResponse(array $response): array;
}
