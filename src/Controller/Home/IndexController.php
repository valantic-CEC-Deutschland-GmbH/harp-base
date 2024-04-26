<?php
declare(strict_types=1);

namespace App\Controller\Home;

use Htmxfony\Template\TemplateBlock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_home_index')]
    public function __invoke(): Response
    {

        $path = __DIR__ . '/../../../example-data/navigation/navigation.json';
        var_dump($path);

        $navigationData = file_get_contents($path);


        return $this->htmxRenderBlock(
            new TemplateBlock(
                'HOME/header.html.twig',
                'header',
                [],
            ),
            new TemplateBlock(
                'HOME/navigation.html.twig',
                'navigation',
                [ 'navigationData' => json_decode($navigationData, true, 512, JSON_THROW_ON_ERROR) ],
            ),
            new TemplateBlock(
                'HOME/header.html.twig',
                'header',
                [],
            ),
        );
    }
}
