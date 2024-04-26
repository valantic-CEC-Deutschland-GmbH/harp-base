<?php

declare(strict_types = 1);

namespace App\Controller\PLP;

use Htmxfony\Controller\HtmxControllerTrait;
use Htmxfony\Response\HtmxResponse;
use Htmxfony\Template\TemplateBlock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductListPageController extends AbstractController
{
    use HtmxControllerTrait;
    #[Route('/category/{categoryId}', name: 'app_plp_index')]
    public function index(int $categoryId): HtmxResponse
    {
        $plpData = file_get_contents(__DIR__ . '/example-data/Products/products.json');

        return $this->htmxRenderBlock(
            new TemplateBlock(
                'plp/block.html.twig',
                'plp1',
                [ 'plpData' => json_decode($plpData, true, 512, JSON_THROW_ON_ERROR) ],
            ));
    }
}
