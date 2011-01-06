<?php

namespace Bundle\ZendNavigationBundle\Page;

use Zend\Navigation\AbstractPage;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;

class RouterPage extends AbstractPage implements SymfonyPage
{
    private $route;

    private $params;

    private $request;

    private $router;

    public function isActive($recursive = false)
    {
        if ($this->request->getParameter('_route') == $this->route) {
           return (count(array_intersect_assoc($this->params, $this->request->getParameters()) >= count($this->params)));
        }
        return false;
    }

    public function getHref()
    {
        return $this->router->generate($this->route, $this->params);
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            array(
                'route' => $this->route,
                'params' => $this->params,
            )
        );
    }
}
