<?php

declare(strict_types=1);

namespace App\Controller\PLP;

use App\Blocks\TemplateBlockFactory;
use App\DataProvider\DataProviderConfigurationFactory;
use App\DataProvider\NavigationDataProvider;
use App\DataProvider\ProductListDataProvider;
use Htmxfony\Controller\HtmxControllerTrait;
use Htmxfony\Request\HtmxRequest;
use Htmxfony\Response\HtmxResponse;
use Htmxfony\Template\TemplateBlock;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function index(int $categoryId, HtmxRequest $request): HtmxResponse|Response
    {


        if ($request->isHtmxRequest()) {
            $plpData = (
                new ProductListDataProvider(
                    new DataProviderConfigurationFactory(),
                    $this->cacheItemPool
                )
            )->provide($categoryId);
            $data = [
                'data' =>
                    [
                        'plpData' => $plpData
                    ]
            ];
            // do partial rendering
            return $this->htmxRenderBlock(
                new TemplateBlock(
                    'plp/index.html.twig',
                    'main',
                    $data,
                )
            );
        } else {
            $headerData = (new NavigationDataProvider(new DataProviderConfigurationFactory(), $this->cacheItemPool))->provide('MAIN_NAVIGATION');
            $plpData = (new ProductListDataProvider(new DataProviderConfigurationFactory(), $this->cacheItemPool))->provide($categoryId);

            $data = [
                'data' =>
                    [
                        'headerData' => $headerData,
                        'plpData' => $plpData
                    ]
            ];
            return $this->render('plp/index.html.twig', $data);
        }
    }
}
