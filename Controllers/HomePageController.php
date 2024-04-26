<?php

declare(strict_types = 1);

namespace Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    public function index(): string
    {
        return $this->render('index.html.twig')->getContent();
    }
}
