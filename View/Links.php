<?php

namespace Bundle\ZendNavigationBundle\View;

class Links extends Zend\View\Helper\Navigation\Links
{
    protected function _isXhtml()
    {
        return true;
    }
}