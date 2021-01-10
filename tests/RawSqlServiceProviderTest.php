<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Connection;
use Illuminate\Foundation\Application;
use Sulao\RawSql\RawSqlServiceProvider;

class RawSqlServiceProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testProvider()
    {
        $app = new Application(__DIR__);
        $app->register(RawSqlServiceProvider::class);

        $this->assertInstanceOf(Closure::class, Connection::getResolver('mysql'));
        $this->assertInstanceOf(Closure::class, Connection::getResolver('mysql'));
        $this->assertInstanceOf(Closure::class, Connection::getResolver('mysql'));
    }
}
