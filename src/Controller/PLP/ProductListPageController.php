<?php

declare(strict_types=1);

namespace App\Controller\PLP;

use App\DataProvider\DataProviderConfigurationFactory;
use App\DataProvider\ProductListDataProvider;
use Htmxfony\Controller\HtmxControllerTrait;
use Htmxfony\Request\HtmxRequest;
use Htmxfony\Response\HtmxResponse;
use Htmxfony\Template\TemplateBlock;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ProductListPageController extends AbstractController
{
    public function __construct(private ?CacheItemPoolInterface $cacheItemPool = null)
    {
    }

    use HtmxControllerTrait;
    #[Route('/category/{categoryId}', name: 'app_plp_index')]
    public function index(int $categoryId, HtmxRequest $request): HtmxResponse
    {
        $plpData = (new ProductListDataProvider(
            new DataProviderConfigurationFactory(),
            $this->cacheItemPool)
        )->provide($categoryId);

        if ($request->isHtmxRequest()) {
            // do partial rendering
            return $this->htmxRenderBlock(
                new TemplateBlock(
                    'plp/index.html.twig',
                    'products_list',
                    ['plpData' => $plpData],
                )
            );
        } else {
            return $this->htmxRenderBlock(
                new TemplateBlock(
                    'plp/index.html.twig',
                    'head'
                ),
                new TemplateBlock(
                    'plp/index.html.twig',
                    'header'
                ),
                new TemplateBlock(
                    'plp/index.html.twig',
                    'main',
                    ['plpData' => $plpData],
                ),
                new TemplateBlock(
                    'plp/index.html.twig',
                    'footer'
                ),
            );
        }
    }
}
