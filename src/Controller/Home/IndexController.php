<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Blocks\TemplateBlockFactory;
use Htmxfony\Controller\HtmxControllerTrait;
use Htmxfony\Response\HtmxResponse;
use Htmxfony\Request\HtmxRequest;
use Htmxfony\Template\TemplateBlock;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    private TemplateBlockFactory $templateBlockFactory;

    public function __construct(private CacheItemPoolInterface $cacheItemPool)
    {
        $this->templateBlockFactory = new TemplateBlockFactory();
    }
    use HtmxControllerTrait;
    #[Route('/', name: 'app_home_index')]
    public function index(HtmxRequest $request): HtmxResponse|Response
    {
        $path = __DIR__ . '/../../../example-data/navigation/navigation.json';

        $navigationData = file_get_contents($path);

        return $this->htmxRenderBlock(
            new TemplateBlock(
                'home/index.html.twig',
                'head'
            ),
            $this->templateBlockFactory->createHeaderTemplateBlock($this->cacheItemPool),
            new TemplateBlock(
                'home/index.html.twig',
                'main'
            ),
            new TemplateBlock(
                'home/index.html.twig',
                'footer'
            ),
        );
    }
}
