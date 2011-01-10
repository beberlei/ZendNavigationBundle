<?php

namespace Bundle\ZendNavigationBundle\Tests;

class NavigationTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateContainer()
    {
        $router = $this->getMock('\Symfony\Component\Routing\RouterInterface');
        $request = new \Symfony\Component\HttpFoundation\Request();
        $pages = array(
            new \Bundle\ZendNavigationBundle\Page\RouterPage(array('route' => 'test_route', 'params' => array('id' => 1234)))    
        );
        $container = new \Bundle\ZendNavigationBundle\Navigation($router, $request, $pages); 

        $this->assertEquals(1, count($container));
    }      

    public function testFactoryContainer()
    {
        $router = $this->getMock('\Symfony\Component\Routing\RouterInterface');
        $request = new \Symfony\Component\HttpFoundation\Request();

        $configFile = __DIR__ . "/_files/example_nav.yml";

        $container = \Bundle\ZendNavigationBundle\Navigation::factory($router, $request, $configFile);
        $page2 = $container->findBy('label', 'Page2');

        $this->assertInstanceOf('Bundle\ZendNavigationBundle\Page\RouterPage', $page2);
    }
}
