<?php

namespace Bundle\ZendNavigationBundle;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Zend\Navigation\Container;
use Zend\Navigation\AbstractPage;
use Zend\Config\Config;

class Navigation extends Container
{
    protected $router;
    protected $request;

    /**
     * Create a navigation container from a YAML config file.
     *
     * @param RouterInterface $router
     * @param Request $request
     * @param string $configFile
     * @return Navigation
     */
    static public function factory(RouterInterface $router, Request $request, $configFile)
    {
        $pages = \Symfony\Component\Yaml\Yaml::load($configFile);
        return new self($router, $request, $pages);
    }

    public function __construct(RouterInterface $router, Request $request, $pages = array())
    {
        $this->router = $router;
        $this->request = $request;
        $this->setPages($pages);
    }

    public function addPage($page)
    {
        if (is_array($page) || $page instanceof Config) {
            if (isset($page['route']) && !isset($page['type'])) {
                $page['type'] = "Bundle\ZendNavigationBundle\Page\RouterPage";
            } else if (isset($page['uri']) && !isset($page['type'])) {
                $page['type'] = "Bundle\ZendNavigationBundle\Page\UriPage";
            }
            $page = AbstractPage::factory($page);
        }
        parent::addPage($page);
        if ($page instanceof AbstractPage) {
            $page->setRouter($this->router);
            $page->setRequest($this->request);
        }
        return $this;
    }
}
