<?php

namespace Bundle\ZendNavigationBundle\Page;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Marker interface
 */
interface SymfonyPage
{
    public function setRouter(RouterInterface $router);
    public function setRequest(Request $request);
}
