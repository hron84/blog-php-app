<?php

require_once __DIR__ . '/../bootstrap.php';


if(!file_exists(APP_ROOT . '/config/application.key')) {
    $key = generate_application_key();

    print " >> Application key is generated into " . APP_ROOT . '/config/application.key' . PHP_EOL;
    print " >> Please ensure you do not commit it into the sources!";
}



