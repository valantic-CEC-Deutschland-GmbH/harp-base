<?php

declare(strict_types = 1);

namespace App\Blocks;

use App\DataProvider\DataProviderConfigurationFactory;
use App\DataProvider\NavigationDataProvider;
use Htmxfony\Template\TemplateBlock;

class TemplateBlockFactory
{
    public function createHeaderTemplateBlock(): TemplateBlock
    {
        $headerData =
            (new NavigationDataProvider(new DataProviderConfigurationFactory()))->provide('MAIN_NAVIGATION');

        return new TemplateBlock('layout/header.html.twig', 'header', ['headerData' => $headerData]);
    }
}
