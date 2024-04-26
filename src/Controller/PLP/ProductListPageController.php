<?php

declare(strict_types = 1);

namespace App\Controller\PLP;

//use App\DataProvider\ProductListDataProvider;
use Htmxfony\Controller\HtmxControllerTrait;
use Htmxfony\Response\HtmxResponse;
use Htmxfony\Template\TemplateBlock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ProductListPageController extends AbstractController
{
    use HtmxControllerTrait;
    #[Route('/category/{categoryId}', name: 'app_plp_index')]
    public function index(int $categoryId): HtmxResponse
    {
        //$plpData = (new ProductListDataProvider())->provide($categoryId);

        $path = __DIR__ . '/../../../example-data/Products/products.json';
        var_dump($path);

        $plpData = file_get_contents($path);


        return $this->htmxRenderBlock(
            new TemplateBlock(
                'plp/block.html.twig',
                'plp1',
                [ 'plpData' => json_decode($plpData, true, 512, JSON_THROW_ON_ERROR) ],
            ));
    }
}
