<?php
declare(strict_types=1);

namespace App\Controller\Example;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedisController extends AbstractController
{
    private CacheItemPoolInterface $cacheItemPool;

    public function __construct(CacheItemPoolInterface $cacheItemPool)
    {
        $this->cacheItemPool = $cacheItemPool;
    }

    #[Route('/example/redis', name: 'app_example_redis')]
    public function __invoke(): Response
    {
        $cacheItem = $this->cacheItemPool->getItem('test');
        if (!$cacheItem->isHit()) {
            $cacheItem->set('123');
            $this->cacheItemPool->save($cacheItem);
        }

        return $this->render('example/redis.html.twig');
    }
}
