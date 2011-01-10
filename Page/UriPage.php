<?php

namespace Bundle\ZendNavigationBundle\Page;

use Zend\Navigation\AbstractPage;

class UriPage extends AbstractPage
{
    protected $uri;

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getHref()
    {
        return $this->uri;
    }
}