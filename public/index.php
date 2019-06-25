<?php

use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * @var ContainerInterface
 */
$container = require_once(__DIR__ . '/../bootstrap.php');

$router = require APP_ROOT . '/config/routes.php';


// We need this despite if user is logged in or not to provide sessionable anon users.
session_start();


