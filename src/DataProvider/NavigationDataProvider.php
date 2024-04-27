<?php
declare(strict_types=1);

namespace App\DataProvider;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Cache\CacheItemPoolInterface;

class NavigationDataProvider implements DataProviderInterface
{
    /**
     * @param DataProviderConfigurationFactory $factory
     * @param CacheItemPoolInterface|null $cacheItemPool
     */
    public function __construct(
        private DataProviderConfigurationFactory $factory,
        private ?CacheItemPoolInterface          $cacheItemPool
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function provide(string|int $id): array
    {
        if ($this->cacheItemPool !== null) {
            $cacheItem = $this->cacheItemPool->getItem('navigation' . $id);

            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }
        }

        $navigationProviderConfig = $this->factory->createNavigationDataProviderConfiguration();

        $endpoint = $navigationProviderConfig->getEndpoint();

        $client = new Client([
            'headers' => [
                'Accept-Language' => 'en-us',
                'Accept' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Cache-Control' => 'no-cache',
            ],
        ]);

        $response = $client->get($endpoint . '/' . $id, [RequestOptions::HTTP_ERRORS => false]);
        if ($response->getStatusCode() !== 200) {
            return [
                'nodes' => []
            ];
        }

        $navigationData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $navigationData = $this->extractTopNavigation($navigationData);

        if ($this->cacheItemPool !== null) {
            $this->cacheItemPool->save(
                $this->cacheItemPool->getItem('navigation' . $id)
                    ->set($navigationData)
            );
        }

        return $navigationData;
    }

    /**
     * @param mixed $navigationData
     * @return array
     */
    private function extractTopNavigation(mixed $navigationData): array
    {
        return $navigationData['data']['attributes'];
    }
}
