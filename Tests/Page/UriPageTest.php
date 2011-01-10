<?php

namespace Bundle\ZendNavigationBundle\Tests\Page;

use Bundle\ZendNavigationBundle\Page\UriPage;

class UriPageTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $page = new UriPage(array('uri' => 'http://www.whitewashing.de'));
        $this->assertEquals("http://www.whitewashing.de", $page->getUri());
    }

    public function testGetHref()
    {
        $page = new UriPage(array('uri' => 'http://www.whitewashing.de'));
        $this->assertEquals("http://www.whitewashing.de", $page->getHref());
    }

    public function testSetUri()
    {
        $page = new UriPage();
        $page->setUri('http://www.whitewashing.de');
        $this->assertEquals("http://www.whitewashing.de", $page->getUri());
    }
}