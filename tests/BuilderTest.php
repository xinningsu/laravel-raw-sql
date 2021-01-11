<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Foundation\Application;
use Sulao\RawSql\ServiceProvider;

class BuilderTest extends \PHPUnit\Framework\TestCase
{
    protected $builder;

    public function testMySqlToRawSql()
    {
        $builder = $this->getBuilder('mysql');
        $sql = $builder->from('user')
            ->where('id', 1)
            ->where('verified', 1)
            ->where('verified_at', '>', new DateTime('2021-01-09 23:23:00'))
            ->toRawSql();
        $this->assertEquals(
            'select * from `user` where `id` = 1 and `verified` = 1 and `verified_at` > \'2021-01-09 23:23:00\'',
            $sql
        );

        $builder = $this->getBuilder('mysql');
        $sql = $builder->from('user')->where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->toRawSql();
        $this->assertEquals(
            "select * from `user` where (`id` = 1 or `email` = 'test@email.com') and `verified` = 1",
            $sql
        );
    }

    public function testPostgresToRawSql()
    {
        $builder = $this->getBuilder('pgsql');
        $sql = $builder->from('user')->where('id', 1)->toRawSql();
        $this->assertEquals(
            'select * from "user" where "id" = 1',
            $sql
        );

        $builder = $this->getBuilder('pgsql');
        $sql = $builder->from('user')->where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->toRawSql();
        $this->assertEquals(
            'select * from "user" where ("id" = 1 or "email" = \'test@email.com\') and "verified" = 1',
            $sql
        );
    }

    public function testSQLiteToRawSql()
    {
        $builder = $this->getBuilder('sqlite');
        $sql = $builder->from('user')->where('id', 1)->toRawSql();
        $this->assertEquals(
            'select * from "user" where "id" = 1',
            $sql
        );

        $builder = $this->getBuilder('sqlite');
        $sql = $builder->from('user')->where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->toRawSql();
        $this->assertEquals(
            'select * from "user" where ("id" = 1 or "email" = \'test@email.com\') and "verified" = 1',
            $sql
        );
    }

    public function testSqlServerToRawSql()
    {
        $builder = $this->getBuilder('sqlsrv');
        $sql = $builder->from('user')->where('id', 1)->toRawSql();
        $this->assertEquals(
            'select * from [user] where [id] = 1',
            $sql
        );

        $builder = $this->getBuilder('sqlsrv');
        $sql = $builder->from('user')->where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->toRawSql();
        $this->assertEquals(
            "select * from [user] where ([id] = 1 or [email] = 'test@email.com') and [verified] = 1",
            $sql
        );
    }

    protected function getBuilder($driver)
    {
        $app = new Application(__DIR__);
        $provider = new ServiceProvider($app);
        $provider->boot();

        $pdo = $this->createMock(PDO::class);
        $drivers = [
            'mysql' => Illuminate\Database\MySqlConnection::class,
            'pgsql' => Illuminate\Database\PostgresConnection::class,
            'sqlite' => Illuminate\Database\SQLiteConnection::class,
            'sqlsrv' => Illuminate\Database\SqlServerConnection::class,
        ];
        $class = $drivers[$driver];
        $connection = new $class($pdo);

        return $connection->query();
    }
}
