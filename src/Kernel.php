<?php

namespace App;

use Htmxfony\HtmxKernelTrait;
use Htmxfony\Request\HtmxRequest;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * Used instead of HtmxKernelTrait because it's not compatible with RoadRunner.
     *
     * @see HtmxKernelTrait
     */
    public function handle(Request $request, $type = 1, $catch = true): Response
    {
        $request = new HtmxRequest(
            $request->query->all(),
            $request->getPayload()->all() ?? [],
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $_SERVER,
            $request->getPayload()
        );
        return parent::handle($request, $type, $catch);
    }
}
