<?php

namespace Bundle\ZendNavigationBundle\Page;

use Zend\Config\Config;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;

class RouterPage extends AbstractPage
{
    private $route;

    private $params;

    public function isActive($recursive = false)
    {
        if ($this->request->attributes->get('_route') == $this->route) {
           return (count(array_intersect_assoc($this->params, $this->request->query->all())) >= count($this->params));
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
