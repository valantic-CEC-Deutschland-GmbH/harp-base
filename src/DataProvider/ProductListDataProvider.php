<?php

declare(strict_types = 1);

namespace App\DataProvider;

use GuzzleHttp\Client;
use Psr\Cache\CacheItemPoolInterface;

class ProductListDataProvider implements DataProviderInterface
{
    public function __construct(
        private DataProviderConfigurationFactory $factory,
        private ?CacheItemPoolInterface $cacheItemPool = null
    ){
    }

    /**
     * @param string|int $id
     *
     * @return array
     */
    public function provide(string|int $id): array
    {
        if ($this->cacheItemPool !== null) {
            $cacheItem = $this->cacheItemPool->getItem('productList' . $id);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }
        }

        $providerConfig = $this->factory->createProductListDataProvider();

        $endpoint = $providerConfig->getEndpoint();

        $client = new Client([
            'headers' => [
                'Accept-Language' => 'en-us',
                'Accept' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Cache-Control' => 'no-cache',
            ],
        ]);

        $response = $client->get($endpoint, ['query' => ['category' => $id]]);

        $plpData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $plpData = $this->extractAbstractProducts($plpData);

        if ($this->cacheItemPool !== null) {
            $this->cacheItemPool->save(
                $this->cacheItemPool->getItem('productList' . $id)
                    ->set($plpData)
            );
        }

        return $plpData;
    }

    /**
     * @param array $plpData
     *
     * @return array
     */
    private function extractAbstractProducts(array $plpData): array
    {
        return [
            'abstractProducts' => $plpData['data'][0]['attributes']['abstractProducts'],
        ];
    }
}
