<?php

use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Arr;
use AD7six\Dsn\Dsn;
use AD7six\Dsn\DbDsn;


/**
 * Converts JDBC-like DB URL's to PDO DSN.
 *
 * @param string DB URL
 *
 * @return string PDO DSN
 * @throws Exception
 */
function url2dsn($url) {
    // pgsql://user:pass@localhost:5432/db
    $dsn = AD7six\Dsn\Dsn::parse($url);
    $pdodsn = '';

    if($dsn instanceof DbDsn) {
        $pdodsn = $dsn->getEngine() . ':host=' . $dsn->getHost() . ';port=' . $dsn->getPort() . ';dbname=' . $dsn->getDatabase();
        $pdodsn .= ';user=' . $dsn->getUser() . ';password=' . $dsn->getPass() . ';';
    }

    return $pdodsn;
}

function decode_secret($secret) {
    $key = file_get_contents(APP_ROOT . '/config/application.key');

    return openssl_decrypt($secret, 'AES-256-CFB', $key);
}

function encode_secret($secret) {
    $key = file_get_contents(APP_ROOT . '/config/application.key');

    return openssl_encrypt($secret, 'AES-256-CFB', $key);
}

function encode_array($arr) {
    foreach($arr as $key => $value) {
        if(is_array($value) && !empty($value)) {
            $arr[$key] = encode_array($value);
        } else {
            $arr[$key] = encode_secret($value);
        }
    }

    return $arr;
}

function generate_application_key() {
    $key = base64_encode(openssl_random_pseudo_bytes(32));

    if(!file_exists(APP_ROOT . '/config/application.key')) {
        file_put_contents(APP_ROOT . '/config/application.key', $key);
    }

    return $key;
}