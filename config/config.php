<?php

namespace Blog;

use Symfony\Yaml;
use Psr\Container\ContainerInterface;
use Psr\Log\LogLevel;

use function DI\add;
use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;

$diConfig = [
    'log.handlers' => [
        get(Monolog\Handler\RotatingFileHandler::class)
    ],

    'log.level' => factory(function(ContainerInterface $c) {
        if(Environment::isDevelopment()) {
            return LogLevel::DEBUG;
        } else {
            return LogLevel::INFO;
        }
    }),

    'log.filename' => factory(function(ContainerInterface $c) { return APP_ROOT . '/log/' . $_ENV['APP_ENV'] . '.log'; }),

    Monolog\Handler\RotatingFileHandler::class => create()->constructor(get('log.fileName'), 3, get('log.level')),

    // TODO RollbarHandler


    Psr\Log\LoggerInterface::class => factory(function(ContainerInterface $c) {
        $logger = new Monolog\Logger($c->get('app.name'));

        foreach($c->get('log.handlers') as $handler) {
            $logger->pushHandler($handler);
        }

        return $logger;
    }),

];

$yamlConfig = [];

if(file_exists(__DIR__ . '/config.yml')) {
    $yamlConfig = Yaml::parseFile(__DIR__ . '/config.yml');

}

return array_merge($yamlConfig, $diConfig);