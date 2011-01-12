<?php

namespace Bundle\ZendNavigationBundle\Page;

class UriPage extends AbstractPage
{
    /**
     * @var string
     */
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

    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            array(
                'uri' => $this->uri,
            )
        );
    }
}