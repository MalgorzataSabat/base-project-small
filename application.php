<?php
    define('DS', DIRECTORY_SEPARATOR );
    define('ROOT_PATH', realpath(dirname(__FILE__) ) );
    define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application') );
    define('APPLICATION_WEB', realpath( dirname(__FILE__) . '/public') );
    define('PUBLIC_FILES_PATH', realpath(APPLICATION_WEB.DS.'files') );
    define('APPLICATION_DATA', realpath( dirname(__FILE__) . '/data' ) );
    define( '_SERVICE_ACCESS', true );
    define( 'OPEN_ENV', (bool)getenv('OPEN_ENV') );

    include APPLICATION_PATH.DS.'config'.DS.'config.php';

    // --- CLI or WWW
    $script_name = pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_BASENAME);
    if ($script_name === 'zf'){
        define('CLI', true);
    } else {
        define('CLI', false);
    }

    // Define application environment
   // if((isset($_COOKIE[COOKIE_DEV]) && $_COOKIE[COOKIE_DEV]) || CLI) {
        define('APPLICATION_ENV', 'development');
        define('DEBUG', true);
        define('DEV', true);
   // } else {
  //      define('APPLICATION_ENV', 'production');
  //      define('DEBUG', false);
  //      define('DEV', false);
   //     register_shutdown_function('shutdown');
  //  }

    // disable cache in application - only in developer environment
   // if( DEBUG && @$_COOKIE[COOKIE_NOCACHE] || CLI){
        define('CACHE_USE', false);
   // }else{
     //   define('CACHE_USE', true);
   // }

    // enable ZF Debuger - only in developer environment
    if( DEBUG && @$_COOKIE[COOKIE_ZFDEBUG]){
        define('ZFDEBUG_USE', true);
    }else{
        define('ZFDEBUG_USE', false);
    }

    // set error reporting level
    if( DEBUG ){ ini_set('display_errors', 1); error_reporting(E_ALL); }
    else{ ini_set('display_errors', 0); error_reporting(0); }

	set_include_path(implode( PATH_SEPARATOR, array(
        ROOT_PATH.'/library',
        APPLICATION_PATH.'/models',
        get_include_path(),
    )));
        
    require ROOT_PATH.'/library/vendor/autoload.php';

    // Create application, bootstrap, and run
    require_once 'Zend/Application.php';
    $application = new Zend_Application( APPLICATION_ENV, APPLICATION_PATH . '/config/application.ini' );
    Zend_Registry::set('application', $application);

    if ( !defined('CLI') || CLI === false ) {
        $application->bootstrap()->run();
    }


/**
 * Shutdown function
 * write error to file
 */
function shutdown()
{
    // get all errors
    $error = error_get_last();
    // if fatala error & parse error
    if ($error) {
        $error_file = APPLICATION_DATA . '/logs/log_'.$error['type'].'.log';

        // set content of error file
        $content = 'Date: ' . date('Y-m-d H:i:s') . PHP_EOL;
        $content .= 'Message: ' . $error['message'] . PHP_EOL;
        $content .= 'File: ' . $error['file'] . PHP_EOL;
        $content .= 'Line: ' . $error['line'] . PHP_EOL;
        $content .= 'Request Uri: ' . @$_SERVER['REQUEST_URI'] . PHP_EOL;
        $content .= 'Request Method: ' . @$_SERVER['REQUEST_METHOD'] . PHP_EOL;
        $content .= 'Request Params: ' . @json_encode($_REQUEST) . PHP_EOL;
        $content .= '-----------------------------------' . PHP_EOL;

        // write content to file
        $content.= file_get_contents($error_file, false, null, null, 1024*1024); // last 1Mb of log;
        $handle = fopen( $error_file, 'w' );
        fwrite($handle, $content);

        if ( $error['type'] == E_ERROR || $error['type'] == E_PARSE ) {
            ob_clean();

            // show 503
            if(strpos($_SERVER['REDIRECT_URL'], '503.html')) {
                header('HTTP/1.1 503 Service Unavailable');
                echo file_get_contents(APPLICATION_WEB . '/503-page.html');
                exit;
            }

            header('Location: /503.html');
        }

        fclose($handle);
    }
}

