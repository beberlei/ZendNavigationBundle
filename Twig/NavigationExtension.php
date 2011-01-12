<?php

namespace Bundle\ZendNavigationBundle\Twig;

use Zend\Navigation\Container;
use Zend\Navigation\AbstractPage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\Engine;

class NavigationExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\Engine
     */
    protected $templating;

    /**
     * @var array
     */
    protected $navContainers = array();

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

    /**
     * @param Engine $engine
     */
    public function setTemplating(Engine $engine)
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
            'nav_sitemap' => new \Twig_Function_Method($this, 'renderSitemap', array('is_safe' => array('html'))),
            'nav_links' => new \Twig_Function_Method($this, 'renderLinks', array('is_safe' => array('html'))),
        );
    }

    public function renderLinks($containerName, $options)
    {
        $links = new \Bundle\ZendNavigationBundle\View\Links($options);
        return $links->render($this->container->get('zend.navigation.'.$containerName));
    }

    public function renderSitemap($containerName, $options = array())
    {
        $sitemap = new \Bundle\ZendNavigationBundle\View\Sitemap($options);
        $sitemap->setServerUrl($this->container->get('request')->getUri());
        return $sitemap->render($this->container->get('zend.navigation.' . $containerName));
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
        if (!isset($options['max_depth'])) {
            $options['max_depth'] = null;
        }
        if (!isset($options['min_depth'])) {
            $options['min_depth'] = 1;
        }

        $navContainer = $this->container->get('zend.navigation.' . $containerName);
        $activePage = $this->findActive($navContainer, $options['min_depth'], $options['max_depth']);
        if (!$activePage) {
            return "";
        }

        $activePage = $activePage['page'];
        $pages = array($activePage);
        // walk back to root
        while ($parent = $activePage->getParent()) {
            if ($parent instanceof AbstractPage) {
                $pages[] = $parent;
            }

            if ($parent === $navContainer) {
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