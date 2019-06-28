<?php
namespace Blog;

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;


class Application {
    /**
     * @var LoggerInterface Application logger
     */

    public $logger;

    /**
     * @var ContainerInterface Application container
     */

    public $container;

    public function __construct(LoggerInterface $logger, ContainerInterface $container) {
        $this->logger = $logger;
        $this->container = $container;
    }

    public function db() {
        
    }
}