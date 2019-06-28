<?php

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use Blog\Environment;

use function DI\create;
use function DI\factory;
use function DI\get;

return [
    'app.name' => 'Foobar',
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

    'log.fileName' => factory(function(ContainerInterface $c) { return APP_ROOT . '/log/' . $_ENV['APP_ENV'] . '.log'; }),

    Monolog\Handler\RotatingFileHandler::class => create()->constructor(get('log.fileName'), 3, get('log.level')),

    Monolog\Handler\HandlerInterface::class => create()->constructor(get('log.fileName'), 3, get('log.level')),
    // Psr\Log\LoggerInterface::class => create(Monolog\Logger::class)->constructor(get('app.name'), get(Monolog\Handler\HandlerInterface::class)),

    Psr\Log\LoggerInterface::class => factory(function(ContainerInterface $c) {
        $logger = new Monolog\Logger($c->get('app.name'));

        foreach($c->get('log.handlers') as $handler) {
            $logger->pushHandler($handler);
        }

        return $logger;
    })
];