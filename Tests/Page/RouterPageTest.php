<?php

namespace Bundle\ZendNavigationBundle\Tests\Page;

use Bundle\ZendNavigationBundle\Page\RouterPage;

class RouterPageTest extends \PHPUnit_Framework_TestCase
{
    private $request;
    private $router;

    public function setUp()
    {
        $this->request = new \Symfony\Component\HttpFoundation\Request();
        $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface');
    }

    public function testConstruct()
    {
        $page = new RouterPage(array(
            'request' => $this->request,
            'router' => $this->router
        ));

        $this->assertSame($this->request, $page->getRequest());
        $this->assertSame($this->router, $page->getRouter());
    }

    public function testGetHref()
    {
        $page = new RouterPage(array(
            'route' => 'foo_route',
            'params' => array('id' => 1234),
            'router' => $this->router
        ));

        $this->router->expects($this->once())
                     ->method('generate')
                     ->with($this->equalTo('foo_route'), $this->equalTo(array('id' => 1234)))
                     ->will($this->returnValue('/foo/route/id/1234'));

        $this->assertEquals('/foo/route/id/1234', $page->getHref());
    }

    public function testIsActive()
    {
        $page = new RouterPage(array(
            'route' => 'foo_route',
            'params' => array('id' => 1234),
            'request' => $this->request,
        ));

        $this->request->attributes->set('_route', 'foo_route');
        $this->request->query->set('id', 1234);
        $this->request->query->set('foo', 'bar');

        $this->assertTrue($page->isActive());
    }

    public function testIsNotActive()
    {
        $page = new RouterPage(array(
            'route' => 'foo_route',
            'params' => array('id' => 1234),
            'request' => $this->request,
        ));

        $this->request->attributes->set('_route', 'foo_route');
        $this->request->query->set('id', 1235);

        $this->assertFalse($page->isActive());
    }
}