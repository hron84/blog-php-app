<?php
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Illuminate\Support\Arr;

$yamlConfig = [];

try {
    if(file_exists(__DIR__ . '/../config/config.yml')) {
        $yamlConfig = Yaml::parseFile(__DIR__ . '/../config/config.yml');
    }
    
    if(file_exists(__DIR__ . '/../config/config.' . $_ENV['APP_ENV'] . '.yml')) {
        $yamlConfig = array_merge($yamlConfig, Yaml::parseFile(__DIR__ . '/../config/config.' . $_ENV['APP_ENV'] . '.yml'));
    }    
} catch(ParseException $ex) {
    print $ex;
}


$yamlConfig = Arr::dot($yamlConfig);

return $yamlConfig;