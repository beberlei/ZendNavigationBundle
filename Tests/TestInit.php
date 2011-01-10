<?php


use Symfony\Component\HttpFoundation\UniversalClassLoader;

$symfonyDir = $GLOBALS['SYMFONY_PATH'];
$loader = $symfonyDir . 'src/Symfony/Component/HttpFoundation/UniversalClassLoader.php';
require_once $loader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'                        => $symfonyDir.'/src',
    'Zend'                           => $symfonyDir.'/vendor/zend/library',
));
$loader->register();

require_once __DIR__ . "/../Navigation.php";
require_once __DIR__ . "/../Page/SymfonyPage.php";
require_once __DIR__ . "/../Page/RouterPage.php";
require_once __DIR__ . "/../Page/UriPage.php";
