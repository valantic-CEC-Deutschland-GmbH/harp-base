<?php

declare(strict_types = 1);

namespace App\DataProvider;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Cache\CacheItemPoolInterface;

class NavigationDataProvider implements DataProviderInterface
{
    /**
     * @param \App\DataProvider\DataProviderConfigurationFactory $factory
     * @param \Psr\Cache\CacheItemPoolInterface|null $cacheItemPool
     */
    public function __construct(
        private DataProviderConfigurationFactory $factory,
        private CacheItemPoolInterface          $cacheItemPool
    ) {
    }

    /**
     * @inheritDoc
     */
    public function provide(string|int $id): array
    {
        $navigationProviderConfig = $this->factory->createNavigationDataProviderConfiguration();
        $endpoint = $navigationProviderConfig->getEndpoint();
        $initialEndPoint = $endpoint;
//        $cacheItem = $this->cacheItemPool->getItem(hash('sha256', $initialEndPoint) . 'navigation' . $id);
//
//        if ($cacheItem->isHit()) {
//            return $cacheItem->get();
//        }
        $dataTransformer = $navigationProviderConfig->getDataTransformer();
        $inputParameters = $dataTransformer->transformInputParameters(['navigationId' => $id]);

        $pathParameters = $inputParameters['pathParameters'] ?? [];
        foreach ($pathParameters as $value) {
            $endpoint .= '/' . $value;
        }
        $queryParameters = $inputParameters['queryParameters'] ?? [];

        $headers = $inputParameters['headers'] ?? [];

        $client = new Client([
            'headers' => $headers,
        ]);
        if ($inputParameters['verb'] === 'POST') {

            $response = $client->post($endpoint, [
                RequestOptions::JSON => json_encode($inputParameters['body']),
                RequestOptions::HTTP_ERRORS => false,
            ]);
        } else {
            $response = $client->get($endpoint, [RequestOptions::HTTP_ERRORS => false]);
        }
        if ($response->getStatusCode() === 200) {
            $navigationData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            $navigationData = $dataTransformer->transformResponse($navigationData);
        } else {
            $navigationData = [
                'nodes' => []
            ];
        }

        $this->cacheItemPool->save(
            $this->cacheItemPool->getItem(hash('sha256', $initialEndPoint) . 'navigation' . $id)
                ->set($navigationData)
        );

        return $navigationData;
    }

    /**
     * @param mixed $navigationData
     *
     * @return array
     */
    private function extractTopNavigation(mixed $navigationData): array
    {
        return $navigationData['data']['attributes'];
    }
}
