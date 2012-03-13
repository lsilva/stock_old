<?php
define('BASE_URL',substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'] ,'/public/index.php'))) ;

$path = dirname(__FILE__);#204102150
// Define path to application directory/
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath($path . '/../application'));
    
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define o endereço do site
defined('HTTP_ROOT')
    || define('HTTP_ROOT',"http://".$_SERVER["HTTP_HOST"]."/private/stock");
            
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath($path . '/../library'),
    get_include_path(),
)));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/models'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();