<?php

declare(strict_types = 1);

namespace RequestHandlers;

use Controllers\HomePageController;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;

class HomePageHandler
{
    private Psr17Factory $psr17Factory;

    public function __construct()
    {
        $this->psr17Factory = new Psr17Factory();
    }

    /**
     * @return \Nyholm\Psr7\Response
     */
    public function handleRequest(): Response
    {
        $res = $this->psr17Factory->createResponse();
        $content = (new HomePageController())->index();
        $res->getBody()->write($content);

        return $res;
    }
}
