<?php

namespace Bundle\ZendNavigationBundle\Page;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Zend\Config\Config;

/**
 * Marker interface
 */
abstract class AbstractPage extends \Zend\Navigation\AbstractPage
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;

        foreach ($this->_pages AS $page) {
            if ($page instanceof AbstractPage) {
                $page->setRouter($router);
            }
        }
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        foreach ($this->_pages AS $page) {
            if ($page instanceof AbstractPage) {
                $page->setRequest($request);
            }
        }
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param  array|Config $page
     * @return AbstractPage
     */
    public function addPage($page)
    {
        if (is_array($page) || $page instanceof Config) {
            if (isset($page['route']) && !isset($page['type'])) {
                $page['type'] = "Bundle\ZendNavigationBundle\Page\RouterPage";
            } else if (isset($page['uri']) && !isset($page['uri'])) {
                $page['type'] = "Bundle\ZendNavigationBundle\Page\UriPage";
            }
            $page = AbstractPage::factory($page);
        }
        parent::addPage($page);
        return $this;
    }
}
