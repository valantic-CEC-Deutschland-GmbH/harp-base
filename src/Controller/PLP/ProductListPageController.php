<?php

declare(strict_types = 1);

namespace App\Controller\PLP;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductListPageController extends AbstractController
{
    #[Route('/category/:categoryId', name: 'app_plp_index')]
    public function __invoke(): Response
    {
        return $this->render('plp/index.html.twig');
    }
}
