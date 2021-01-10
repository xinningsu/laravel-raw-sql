<?php

namespace Sulao\RawSql;

use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

/**
 * Class RawSqlServiceProvider
 *
 * @package Sulao\RawSql
 */
class RawSqlServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $drivers = [
            'mysql' => MySqlConnection::class,
            'pgsql' => PostgresConnection::class,
            'sqlite' => SQLiteConnection::class,
            'sqlsrv' => SqlServerConnection::class,
        ];

        foreach ($drivers as $driver => $connection) {
            Connection::resolverFor(
                $driver,
                function ($pdo, $database, $prefix, $config) use ($connection) {
                    return new $connection(
                        $pdo,
                        $database,
                        $prefix,
                        $config
                    );
                }
            );
        }
    }
}
