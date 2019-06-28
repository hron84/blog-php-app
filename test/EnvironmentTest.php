<?php
/**
 * Created by PhpStorm.
 * User: 20150
 * Date: 2019. 06. 28.
 * Time: 14:50
 */

namespace Blog\Test;

use Blog\Environment;

use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase {
    public function testIsWithKnownEnvName() {
        $this->setEnv('development');

        $this->assertTrue( Environment::is('development'));
    }

    public function testIsWithUnknownEnvName() {
        $this->setEnv('staging');

        $this->assertTrue( Environment::is('staging'));
    }

    public function testIsDevelopment() {
        $this->setEnv('development');

        $this->assertTrue( Environment::isDevelopment());
    }

    public function testIsTest() {
        $this->setEnv('test');

        $this->assertTrue( Environment::isTest());
    }

    public function testIsProduction() {
        $this->setEnv('production');

        $this->assertTrue( Environment::isProduction());
    }

    public function testWithDevelopmentEnv() {
        $this->setEnv('development');

        $this->assertTrue( Environment::isDevelopment());
        $this->assertFalse(Environment::isTest());
        $this->assertFalse(Environment::isProduction());
        $this->assertTrue( Environment::isPlayground());
    }

    public function testWithTestEnv() {
        $this->setEnv('test');

        $this->assertFalse(Environment::isDevelopment());
        $this->assertTrue( Environment::isTest());
        $this->assertFalse(Environment::isProduction());
        $this->assertTrue( Environment::isPlayground());
    }

    public function testWithProductionEnv() {
        $this->setEnv('production');

        $this->assertFalse(Environment::isDevelopment());
        $this->assertFalse(Environment::isTest());
        $this->assertTrue( Environment::isProduction());
        $this->assertFalse(Environment::isPlayground());
    }


    private function setEnv($env) {
        $_ENV['APP_ENV'] = $env;
    }
}