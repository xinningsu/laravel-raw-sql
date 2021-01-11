<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Application;
use Sulao\RawSql\ServiceProvider;

class ServiceProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testProvider()
    {
        $app = new Application(__DIR__);
        $provider = new ServiceProvider($app);
        $provider->boot();

        $this->assertTrue(QueryBuilder::hasMacro('toRawSql'));
    }
}
