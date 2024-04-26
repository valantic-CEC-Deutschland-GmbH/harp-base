<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Htmxfony\Controller\HtmxControllerTrait;
use Htmxfony\Response\HtmxResponse;
use Htmxfony\Request\HtmxRequest;
use Htmxfony\Template\TemplateBlock;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    use HtmxControllerTrait;
    #[Route('/', name: 'app_home_index')]
    public function index(HtmxRequest $request): HtmxResponse|Response
    {
        if ($request->isHtmxRequest()) {
            // do partial rendering
            return $this->htmxRenderBlock(
                new TemplateBlock(
                    'home/index.html.twig',
                    'main'
                )
            );
        } else {
            // render full page
            return $this->render(
                'home/index.html.twig'
            );
        }
    }
}
