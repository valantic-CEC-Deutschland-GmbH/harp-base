<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Blocks\TemplateBlockFactory;
use Htmxfony\Controller\HtmxControllerTrait;
use Htmxfony\Response\HtmxResponse;
use Htmxfony\Request\HtmxRequest;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\DataProvider\NavigationDataProvider;
use App\DataProvider\DataProviderConfigurationFactory;

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


        $headerData = (new NavigationDataProvider(new DataProviderConfigurationFactory(), $this->cacheItemPool))->provide('MAIN_NAVIGATION');

        $data = [
            'data' =>
                [
                    'headerData' => $headerData,
                ]
        ];
        return $this->render('home/index.html.twig', $data);

    }
}
