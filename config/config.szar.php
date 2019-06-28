<?php

namespace Blog;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use function DI\add;
use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;

return [
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

    // Psr\Log\LoggerInterface::class => factory(function(ContainerInterface $c) {
    //     $logger = new Monolog\Logger($c->get('app.name'));

    //     foreach($c->get('log.handlers') as $handler) {
    //         $logger->pushHandler($handler);
    //     }

    //     return $logger;
    // }),

    Monolog\Formatter\LineFormatter::class => create()->method('ignoreEmptyContextAndExtra', true)->method('includeStacktraces', true),

    
    // Psr\Log\LoggerInterface::class => factory(function (ContainerInterface $c) {
    //     $logger = new Monolog\Logger($c->get('app.name'));

    //     $handler = $c->get(Monolog\Handler\RotatingFileHandler::class);
    //     $formatter = new Monolog\Formatter\LineFormatter();
    //     $formatter->ignoreEmptyContextAndExtra(true);
    //     $formatter->includeStacktraces(true);
    //     $handler->setFormatter($formatter);

    //     $logger->pushHandler($handler);

    //     return $logger;
    // }),
    'Psr\Log\LoggerInterface' => create(Monolog\Logger::class)
            ->method('setFormatter', get(Monolog\Formatter\LineFormatter::class))
            ->method('pushHandler', get(Monolog\Handler\RotatingFileHandler::class)),
];