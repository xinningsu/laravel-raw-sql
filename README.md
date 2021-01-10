# Laravel Raw SQL

Get raw sql from laravel query builder via toSql method.

[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE)
[![Build Status](https://api.travis-ci.org/xinningsu/laravel-raw-sql.svg?branch=master)](https://travis-ci.org/xinningsu/laravel-raw-sql)
[![Coverage Status](https://coveralls.io/repos/github/xinningsu/laravel-raw-sql/badge.svg?branch=master)](https://coveralls.io/github/xinningsu/laravel-raw-sql)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xinningsu/laravel-raw-sql/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xinningsu/laravel-raw-sql)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/xinningsu/laravel-raw-sql/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/g/xinningsu/laravel-raw-sql)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=xinningsu_laravel-raw-sql&metric=alert_status)](https://sonarcloud.io/dashboard?id=xinningsu_laravel-raw-sql)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=xinningsu_laravel-raw-sql&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=xinningsu_laravel-raw-sql)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=xinningsu_laravel-raw-sql&metric=security_rating)](https://sonarcloud.io/dashboard?id=xinningsu_laravel-raw-sql)
[![Maintainability](https://api.codeclimate.com/v1/badges/18669386ce65532b228f/maintainability)](https://codeclimate.com/github/xinningsu/laravel-raw-sql/maintainability)


# Background

Using `toSql` method to output the sql of query builder, it's something with `?`, not the raw sql.

```php
use Illuminate\Support\Facades\DB;
use App\Models\User;


echo DB::table('user')->where('id', 1)->where('verified', 1)->toSql();
// or 
echo User::where('id', 1)->where('verified', 1)->toSql();
```

The output would be something like this with `?`:

```mysql
select * from `user` where `id` = ? and `verified` = ?
```

I exactly want the raw SQL like this:

```mysql
select * from `user` where `id` = 1 and `verified` = 1
```

Now with this package, we can get the raw sql via toSql method with `true` as first parameter.

```php
echo DB::table('user')->where('id', 1)->where('verified', 1)->toSql(true);
// or 
echo User::where('id', 1)->where('verified', 1)->toSql(true);
```

Will output

```mysql
select * from `user` where `id` = 1 and `verified` = 1
```


# Installation

Require this package with composer. 

```
composer require xinningsu/laravel-raw-sql

```

As this package using laravel [Package Discovery](https://laravel.com/docs/8.x/packages#package-discovery) to discover `Sulao\RawSql\RawSqlServiceProvider::class`, so there's no need to do anything else, please use it directly.



# License

[MIT](./LICENSE)
