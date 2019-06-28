<?php

require __DIR__ . '/../bootstrap.php';

use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase {
    public function testUrl2DsnCanCreateDSN() {
        $url = "pgsql://bloguser:Secret123!@db.pgdb.com:5432/blog_development";
        $dsn = "pgsql:host=db.pgdb.com;port=5432;dbname=blog_development;user=bloguser;password=Secret123!;";

        $this->assertTrue(function_exists('url2dsn'));

        $this->assertEquals(
            $dsn, 
            url2dsn($url)
        );
    }
}