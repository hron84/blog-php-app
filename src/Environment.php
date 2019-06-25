<?php

namespace Blog;

class Environment {

    const DEVELOPMENT = 'development';
    const TEST = 'test';
    const PRODUCTION = 'production';
    /**
     * Checks the application environment based on the OS environment matches with the specified one
     * 
     * @return bool True if the APP_ENV environment variable matches false if not.
     */
    public static function is($envSpec) {
        return !empty($_ENV['APP_ENV']) && strtolower($_ENV['APP_ENV']) === strtolower($envSpec);
    }

    /**
     * Shorthand for is(Environment::DEVELOPMENT)
     * 
     * @api
     * @see Environment::is()
     * 
     * @return bool
     */
    public static function isDevelopment() {
        return self::is(self::DEVELOPMENT);
    }

    /**
     * Shorthand for is(Environment::TEST)
     * 
     * @api
     * @see Environment::is()
     * 
     * @return bool
     */

    public static function isTest() {
        return self::is(self::TEST);
    }

    /**
     * Shorthand for is(Environment::PRODUCTION)
     * 
     * @api
     * @see Environment::is()
     * 
     * @return bool
     */

    public static function isProduction() {
        return self::is(self::PRODUCTION);
    }


    /**
     * Checks if we running in a production-like environment or not. If the actual environment is one of Environment::DEVELOPMENT or Environment::TEST
     * we consider it as a non-production like environment. 
     * 
     * @api
     * @see Environment::isDevelopment()
     * @see Environment::isTest()
     * 
     * @return bool True if the actual environment is one of Environment::DEVELOPMENT or Environment::TEST otherwise false.
     */

    public static function isPlayground() {
        return self::isDevelopment() || self::isTest();
    }

}