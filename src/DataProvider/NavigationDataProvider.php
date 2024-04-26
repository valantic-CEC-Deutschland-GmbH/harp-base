<?php

declare(strict_types = 1);

namespace App\DataProvider;

use GuzzleHttp\Client;

class NavigationDataProvider implements DataProviderInterface
{
    public function __construct(private DataProviderConfigurationFactory $factory)
    {
    }

    /**
     * @inheritDoc
     */
    public function provide(int $id): array
    {
        $navigationProviderConfig = $this->factory->createNavigationDataProvider();

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

        $response = $client->get($endpoint . '/' . $id);

        $navigationData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $navigationData = $this->extractTopNavigation($navigationData);

        return $navigationData;
    }

    /**
     * @param mixed $navigationData
     *
     * @return array
     */
    private function extractTopNavigation(mixed $navigationData): array
    {
        return $navigationData['data'][0]['attributes'];
    }
}
