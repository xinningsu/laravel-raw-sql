<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Sulao\RawSql\MySqlConnection;
use Sulao\RawSql\PostgresConnection;
use Sulao\RawSql\SqlServerConnection;
use Sulao\RawSql\SQLiteConnection;

class BuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testMySqlToRawSql()
    {
        $builder = $this->getBuilder('mysql');
        $sql = $builder->from('users')
            ->where('id', 1)
            ->where('verified', true)
            ->where('verified_at', '>', new DateTime('2021-01-09 23:23:00'))
            ->toSql(true);
        $this->assertEquals(
            'select * from `users` where `id` = 1 and `verified` = 1 and `verified_at` > \'2021-01-09 23:23:00\'',
            $sql
        );

        $builder = $this->getBuilder('mysql');
        $sql = $builder->from('users')->where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->toSql(true);
        $this->assertEquals(
            "select * from `users` where (`id` = 1 or `email` = 'test@email.com') and `verified` = 1",
            $sql
        );
    }

    public function testPostgresToRawSql()
    {
        $builder = $this->getBuilder('pgsql');
        $sql = $builder->from('users')->where('id', 1)->toSql(true);
        $this->assertEquals(
            'select * from "users" where "id" = 1',
            $sql
        );

        $builder = $this->getBuilder('pgsql');
        $sql = $builder->from('users')->where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->toSql(true);
        $this->assertEquals(
            'select * from "users" where ("id" = 1 or "email" = \'test@email.com\') and "verified" = 1',
            $sql
        );
    }

    public function testSQLiteToRawSql()
    {
        $builder = $this->getBuilder('sqlite');
        $sql = $builder->from('users')->where('id', 1)->toRawSql();
        $this->assertEquals(
            'select * from "users" where "id" = 1',
            $sql
        );

        $builder = $this->getBuilder('sqlite');
        $sql = $builder->from('users')->where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->toRawSql();
        $this->assertEquals(
            'select * from "users" where ("id" = 1 or "email" = \'test@email.com\') and "verified" = 1',
            $sql
        );
    }

    public function testSqlServerToRawSql()
    {
        $builder = $this->getBuilder('sqlsrv');
        $sql = $builder->from('users')->where('id', 1)->toRawSql();
        $this->assertEquals(
            'select * from [users] where [id] = 1',
            $sql
        );

        $builder = $this->getBuilder('sqlsrv');
        $sql = $builder->from('users')->where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->toRawSql();
        $this->assertEquals(
            "select * from [users] where ([id] = 1 or [email] = 'test@email.com') and [verified] = 1",
            $sql
        );
    }

    protected function getBuilder($driver)
    {
        $pdo = $this->createMock(PDO::class);
        $drivers = [
            'mysql' => MySqlConnection::class,
            'pgsql' => PostgresConnection::class,
            'sqlite' => SQLiteConnection::class,
            'sqlsrv' => SqlServerConnection::class,
        ];
        $class = $drivers[$driver];
        $connection = new $class($pdo);

        return $connection->query();
    }
}
