<?php

use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;
use Psr\Log\LogLevel;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

define('APP_ROOT', __DIR__);

require_once 'vendor/autoload.php';

// Very early load of Dotenv. This is required because we build container, database, etc by .env 

$varorder = ini_get('variables_order');

if(strpos($varorder, 'E') === false) {
    print 'WARNING: Symfony Dotenv works perfectly only if variables_order set to contain environment (E)';
}

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

// Early Monolog setup. This will be replaced later with the application-defined logger.

$bootstrapLogger = new Logger('bootstrap');
$bootstrapLogger->pushHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, LogLevel::INFO));

$container = null;

try {
    $containerBuilder = new ContainerBuilder();

    $containerBuilder = $containerBuilder->useAutowiring()->useAnnotations();

    if('development' !== $_ENV['APP_ENV']) {
        $containerBuilder = $containerBuilder->ignorePhpDocErrors();
    }

    // Base config - everything goes here!
    if(file_exists(APP_ROOT . '/config/config.php')) {
        $containerBuilder->addDefinitions(APP_ROOT . '/config/config.php');
    }

    // Environment - specific config. Usually not required, but some services could be overridden
    // like fake API classes for tests. Be careful using PREFIX\Test classes since they're only loadable
    // in development or test environment!
    if(file_exists(APP_ROOT . '/config/config.' . $_ENV['APP_ENV'] . '.php')) {
        $containerBuilder->addDefinitions(APP_ROOT . '/config/config.' . $_ENV['APP_ENV'] . '.php');
    }

    if(file_exists(APP_ROOT . '/config/config.local.php')) {
        $containerBuilder->addDefinitions(APP_ROOT . '/config/config.local.php');
    }


    $container = $containerBuilder->build();

    if($container->has('session.handler')) {
        try {
            $shandler = $container->get('session.handler');
        } catch(ContainerExceptionInterface $ex) {
            $shandler = null;
        }
    
        if(null !== $shandler) {
            session_set_save_handler($shandler);
        }
    }
    
    /**
     * @var Psr\Log\LoggerInterface
     */
    $logger = $container->get(Psr\Log\LoggerInterface::class);

    Monolog\ErrorHandler::register($logger);

    return $container;

} catch(Exception $ex) {
    $bootstrapLogger->error('Application bootstrap failed: ' . $ex, 
        [
            'APP_ENV' => $_ENV['APP_ENV'], 
            'variables_order' => $varorder,
        ]
    );
}