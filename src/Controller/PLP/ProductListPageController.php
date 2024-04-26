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
use Symfony\Component\Routing\Attribute\Route;

class ProductListPageController extends AbstractController
{
    use HtmxControllerTrait;
    #[Route('/category/{categoryId}', name: 'app_plp_index')]
    public function index(int $categoryId, HtmxRequest $request): HtmxResponse
    {
//        if ($request->isHtmxRequest()) {
//            // do partial rendering
//        }
//        else {
//            // render full page
//        }
        $plpData = (new ProductListDataProvider(new DataProviderConfigurationFactory()))->provide($categoryId);

//        $path = __DIR__ . '/../../../example-data/Products/products.json';
//        var_dump($path);

//        $plpData = file_get_contents($path);
//

        return $this->htmxRenderBlock(
            new TemplateBlock(
                'plp/block.html.twig',
                'plp1',
                [ 'plpData' => $plpData ],
            ));
    }
}
