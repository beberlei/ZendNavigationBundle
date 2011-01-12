<?php

namespace Bundle\ZendNavigationBundle\Tests\Twig;

use Bundle\ZendNavigationBundle\Twig\NavigationExtension;

class NavigationExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderBreadcrumb()
    {
        $router = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $request = new \Symfony\Component\HttpFoundation\Request(
            array("id" => 1234), array(), array('_route' => 'my_route'), array(), array(), array()
        );
        
        $navContainer = new \Bundle\ZendNavigationBundle\Navigation(
            $router, $request, array(array(
            'uri' => 'http://www.symfony-reloaded.org',
            'label' => 'Foo',
            'pages' => array(
                array('label' => 'Bar', 'route' => 'my_route', 'params' => array('id' => 1234)),
            )
        )));

        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\Engine', array(), array(), '', false);

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $ext = new NavigationExtension($container);

        $container->expects($this->at(0))
                  ->method('get')
                  ->with($this->equalTo('zend.navigation.test'))
                  ->will($this->returnValue($navContainer));
        $container->expects($this->at(1))
                  ->method('get')
                  ->with($this->equalTo('templating'))
                  ->will($this->returnValue($templating));

        $ext->renderBreadcrumb('test', array(
            'separator' => '&raquo;',
            'link_last' => true,
            'min_depth' => 0,
        ));
    }

    public function testSitemap()
    {
        $router = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $router->expects($this->at(0))
               ->method('generate')
               ->with($this->equalTo('my_route'), $this->equalTo(array('id' => 1234)))
               ->will($this->returnValue('/my_route/id/1234'));

        $request = new \Symfony\Component\HttpFoundation\Request(
            array("id" => 1234), array(), array('_route' => 'my_route'), array(), array(), array()
        );
        $request->headers->set('HOST', 'www.symfony-reloaded.org');

        $navContainer = new \Bundle\ZendNavigationBundle\Navigation(
            $router, $request, array(array(
            'uri' => 'http://www.symfony-reloaded.org',
            'label' => 'Foo',
            'pages' => array(
                array('label' => 'Bar', 'route' => 'my_route', 'params' => array('id' => 1234)),
            )
        )));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->at(0))
                  ->method('get')
                  ->with($this->equalTo('request'))
                  ->will($this->returnValue($request));
        $container->expects($this->at(1))
                  ->method('get')
                  ->with($this->equalTo('zend.navigation.test'))
                  ->will($this->returnValue($navContainer));

        $ext = new NavigationExtension($container);

        $urlsetXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>http://www.symfony-reloaded.org</loc></url><url><loc>http://www.symfony-reloaded.org//my_route/id/1234</loc></url></urlset>
XML;

        $this->assertEquals($urlsetXml, $ext->renderSitemap("test"));
    }
}