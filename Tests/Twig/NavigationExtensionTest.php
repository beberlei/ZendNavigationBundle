<?php

namespace Bundle\ZendNavigationBundle\Tests\Twig;

class NavigationExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderBreadcrumb()
    {
        $ext = new \Bundle\ZendNavigationBundle\Twig\NavigationExtension($container);
    }
}