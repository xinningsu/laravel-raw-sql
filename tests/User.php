<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Sulao\RawSql\Builder;
use Sulao\RawSql\MySqlConnection;
use Sulao\RawSql\PostgresConnection;
use Sulao\RawSql\SqlServerConnection;
use Sulao\RawSql\SQLiteConnection;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'users';
}
