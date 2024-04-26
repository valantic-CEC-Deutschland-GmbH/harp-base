<?php

declare(strict_types = 1);

namespace App\Controller\PLP;

//use App\DataProvider\ProductListDataProvider;
use App\DataProvider\DataProviderConfigurationFactory;
use App\DataProvider\ProductListDataProvider;
use Htmxfony\Controller\HtmxControllerTrait;
use Htmxfony\Request\HtmxRequest;
use Htmxfony\Response\HtmxResponse;
use Htmxfony\Template\TemplateBlock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductListPageController extends AbstractController
{
    use HtmxControllerTrait;
    #[Route('/category/{categoryId}', name: 'app_plp_index')]
    public function index(int $categoryId, HtmxRequest $request): HtmxResponse|Response
    {
        $plpData = (new ProductListDataProvider(new DataProviderConfigurationFactory()))->provide($categoryId);

        if ($request->isHtmxRequest()) {
            // do partial rendering
            return $this->htmxRenderBlock(
                new TemplateBlock(
                    'plp/index.html.twig',
                    'main',
                    ['plpData' => $plpData],
                )
            );
        } else {
            // render full page
            return $this->render(
                'plp/index.html.twig',
                ['plpData' => $plpData],
            );
        }
    }
}
