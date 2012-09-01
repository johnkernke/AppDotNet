<?php

use TehBanana\AppDotNet\Core;

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR;
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require_once $fileName;
}

spl_autoload_register('autoload');

require_once dirname(__FILE__) . '/config.php';

session_start();

$adn = new Core($app_adn_client_id, $app_adn_client_secret, $app_adn_redirect_uri);

if ($_SESSION['adn_access_token']) {
    $adn->setAccessToken($_SESSION['adn_access_token']);
}
