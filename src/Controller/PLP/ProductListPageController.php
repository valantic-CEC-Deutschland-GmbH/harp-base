<?php

declare(strict_types=1);

namespace App\Controller\PLP;

use App\Blocks\TemplateBlockFactory;
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
    private TemplateBlockFactory $templateBlockFactory;
    public function __construct(private ?CacheItemPoolInterface $cacheItemPool = null)
    {
        $this->templateBlockFactory = new TemplateBlockFactory();
    }

    use HtmxControllerTrait;
    #[Route('/category/{categoryId}', name: 'app_plp_index')]
    public function index(int $categoryId, HtmxRequest $request): HtmxResponse
    {
        $plpData = (
            new ProductListDataProvider(
                new DataProviderConfigurationFactory(),
                $this->cacheItemPool
            )
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
                $this->templateBlockFactory->createHeaderTemplateBlock($this->cacheItemPool),
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
