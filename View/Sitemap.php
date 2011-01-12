<?php

namespace Bundle\ZendNavigationBundle\View;

use Bundle\ZendNavigationBundle\Page\AbstractPage;

class Sitemap extends \Zend\View\Helper\Navigation\Sitemap
{
    private $encoding = 'UTF-8';

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * Returns an escaped absolute URL for the given page
     *
     * @param  \Zend\Navigation\AbstractPage $page  page to get URL from
     * @return string
     */
    public function url(AbstractPage $page)
    {
        $href = $page->getHref();

        if (!isset($href{0})) {
            // no href
            return '';
        } elseif ($href{0} == '/') {
            // href is relative to root; use serverUrl helper
            $url = $this->getServerUrl() . $href;
        } elseif (preg_match('/^[a-z]+:/im', (string) $href)) {
            // scheme is given in href; assume absolute URL already
            $url = (string) $href;
        } else {
            throw new \RuntimeException("Relative url cannot be rendered.");
        }

        return $this->_xmlEscape($url);
    }

    protected function _xmlEscape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, $this->encoding, false);
    }
}