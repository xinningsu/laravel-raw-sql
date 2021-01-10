<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Sulao\RawSql\RawSqlServiceProvider;

class FunctionalTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->initDb();
    }

    public function testDB()
    {
        $this->assertEquals(
            'select * from "users" where "id" = 1',
            DB::table('users')->where('id', 1)->toRawSql()
        );

        $sql = DB::table('users')->where(function ($query) {
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

    public function testModel()
    {
        $this->assertEquals(
            'select * from "users" where "id" = 1',
            User::where('id', 1)->toSql(true)
        );

        $sql = User::where(function ($query) {
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

    public function testQuery()
    {
        $user = DB::table('users')->where('id', 1)->first();
        $this->assertNotEmpty($user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('sulao', $user->name);
        $this->assertEquals('test@email.com', $user->email);

        $this->assertNull(DB::table('users')->where('id', 2)->first());

        $users = DB::table('users')->where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->get()
            ->toArray();
        $this->assertNotEmpty($users);
        $this->assertEquals(1, $users[0]->id);
        $this->assertEquals('sulao', $users[0]->name);
        $this->assertEquals('test@email.com', $users[0]->email);

        $user = User::find(1);
        $this->assertNotEmpty($user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('sulao', $user->name);
        $this->assertEquals('test@email.com', $user->email);

        $this->assertNotEmpty(User::where('id', 1)->first());
        $this->assertNotEmpty(User::where('id', 1)->get());
        $this->assertNull(User::where('id', 2)->first());

        $user = User::where(function ($query) {
            $query->where('id', 1)
                ->orWhere('email', 'test@email.com');
        })
            ->where('verified', 1)
            ->first();
        $this->assertNotEmpty($user);
    }

    protected function initDb()
    {
        $app = new Application(__DIR__);
        Facade::setFacadeApplication($app);

        $config = new Repository();
        $config->set('database', [
            'default' => 'sqlite',
            'connections' => [
                'sqlite' => [
                    'driver' => 'sqlite',
                    'url' => null,
                    'database' => __DIR__ . '/database.sqlite',
                    'prefix' => '',
                    'foreign_key_constraints' => true,
                ],
            ],
        ]);
        $app->instance('config', $config);

        $app->register(DatabaseServiceProvider::class);
        Model::setConnectionResolver($app['db']);

        $app->register(RawSqlServiceProvider::class);
    }
}
