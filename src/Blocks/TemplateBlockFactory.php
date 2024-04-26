<?php

declare(strict_types = 1);

namespace App\Blocks;

use App\DataProvider\DataProviderConfigurationFactory;
use App\DataProvider\NavigationDataProvider;
use Htmxfony\Template\TemplateBlock;
use Psr\Cache\CacheItemPoolInterface;

class TemplateBlockFactory
{
    /**
     * @param \Psr\Cache\CacheItemPoolInterface|null $cacheItemPool
     *
     * @return \Htmxfony\Template\TemplateBlock
     */
    public function createHeaderTemplateBlock(?CacheItemPoolInterface $cacheItemPool = null): TemplateBlock
    {
        $headerData =
            (new NavigationDataProvider(
                new DataProviderConfigurationFactory(),
                $cacheItemPool,
            ))->provide('MAIN_NAVIGATION');

        return new TemplateBlock('layout/header.html.twig', 'header', ['headerData' => $headerData]);
    }
}
