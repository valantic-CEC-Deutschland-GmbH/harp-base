<?php

declare(strict_types=1);

namespace App\Controller\PLP;

//use App\DataProvider\ProductListDataProvider;
use Htmxfony\Controller\HtmxControllerTrait;
use Htmxfony\Response\HtmxResponse;
use Htmxfony\Request\HtmxRequest;
use Htmxfony\Template\TemplateBlock;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ProductListPageController extends AbstractController
{
    use HtmxControllerTrait;
    #[Route('/category/{categoryId}', name: 'app_plp_index')]
    public function index(int $categoryId, HtmxRequest $request): HtmxResponse|Response
    {
        //$plpData = (new ProductListDataProvider())->provide($categoryId);

        $path = __DIR__ . '/../../../example-data/Products/products.json';
        var_dump($path);

        $plpData = file_get_contents($path);
        if ($request->isHtmxRequest()) {
            // do partial rendering
            return $this->htmxRenderBlock(
                new TemplateBlock(
                    'plp/index.html.twig',
                    'main',
                    ['plpData' => json_decode($plpData, true, 512, JSON_THROW_ON_ERROR)],
                )
            );
        } else {
            // render full page
            return $this->render(
                'plp/index.html.twig',
                ['plpData' => json_decode($plpData, true, 512, JSON_THROW_ON_ERROR)],
            );
        }
    }
}
