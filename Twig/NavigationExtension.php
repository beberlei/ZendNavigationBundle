<?php

namespace Bundle\ZendNavigationBundle\Twig;

use Zend\Navigation\Container;
use Zend\Navigation\AbstractPage;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NavigationExtension extends \Twig_Extension
{
    protected $container;

    protected $templating;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getTemplating()
    {
        if (!$this->templating) {
            $this->templating = $this->container->get('templating');
        }
        return $this->templating;
    }

    public function setTemplating(\Symfony\Component\Templating\Engine $engine)
    {
        $this->templating = $engine;
    }

    public function getName()
    {
        return 'navigation';
    }

    public function getFunctions()
    {
        return array(
            'nav_breadcrumb' => new \Twig_Function_Method($this, 'renderBreadcrumb', array('is_safe' => array('html'))),
        );
    }

    public function renderBreadcrumb($containerName, $options = array())
    {
        if (!isset($options['template'])) {
            $options['template'] = "ZendBundle:breadcrumb.twig";
        }
        if (!isset($options['link_last'])) {
            $options['link_last'] = false;
        } else {
            $options['link_last'] = (bool)$options['link_last'];
        }
        if (!isset($options['separator'])) {
            $options['separator'] = ' &gt; ';
        }

        $navContainer = $this->container->get('zend.navigation.' . $containerName);
        $activePage = $this->findActive($navContainer, 1, null);
        if (!$activePage) {
            return "";
        }

        $pages = array($activePage['page']);
        // walk back to root
        while ($parent = $activePage->getParent()) {
            if ($parent instanceof AbstractPage) {
                $pages[] = $parent;
            }

            if ($parent === $container) {
                // at the root of the given container
                break;
            }

            $activePage = $parent;
        }

        $templating = $this->getTemplating();
        return $templating->render($options['template'], array(
            'separator' => $options['separator'],
            'link_last' => $options['link_last'],
            'pages' => $pages,
        ));
    }

    /**
     * Finds the deepest active page in the given container
     *
     * @param  \Zend\Navigation\Container $container  container to search
     * @param  int|null                  $minDepth   [optional] minimum depth
     *                                               required for page to be
     *                                               valid. Default is to use
     *                                               {@link getMinDepth()}. A
     *                                               null value means no minimum
     *                                               depth required.
     * @param  int|null                  $minDepth   [optional] maximum depth
     *                                               a page can have to be
     *                                               valid. Default is to use
     *                                               {@link getMaxDepth()}. A
     *                                               null value means no maximum
     *                                               depth required.
     * @return array                                 an associative array with
     *                                               the values 'depth' and
     *                                               'page', or an empty array
     *                                               if not found
     */
    public function findActive(Container $container, $minDepth, $maxDepth)
    {

        $found  = null;
        $foundDepth = -1;
        $iterator = new \RecursiveIteratorIterator($container,
                \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $page) {
            $currDepth = $iterator->getDepth();
            if ($currDepth < $minDepth || !$this->accept($page)) {
                // page is not accepted
                continue;
            }

            if ($page->isActive(false) && $currDepth > $foundDepth) {
                // found an active page at a deeper level than before
                $found = $page;
                $foundDepth = $currDepth;
            }
        }

        if (is_int($maxDepth) && $foundDepth > $maxDepth) {
            while ($foundDepth > $maxDepth) {
                if (--$foundDepth < $minDepth) {
                    $found = null;
                    break;
                }

                $found = $found->getParent();
                if (!$found instanceof AbstractPage) {
                    $found = null;
                    break;
                }
            }
        }

        if ($found) {
            return array('page' => $found, 'depth' => $foundDepth);
        } else {
            return array();
        }
    }

    public function accept(AbstractPage $page, $recursive = true)
    {
        // ACL, Visiblity and such
        return true;
    }
}