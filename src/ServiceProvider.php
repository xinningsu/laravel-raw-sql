<?php

namespace Sulao\RawSql;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Class ServiceProvider
 *
 * @package Sulao\RawSql
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        QueryBuilder::macro('toRawSql', function () {
            /**
             * @var $this QueryBuilder
             */
            $sql = $this->toSql();
            $bindings = $this->getBindings();
            $bindings = $this->getConnection()->prepareBindings($bindings);
            $bindings = array_map(function ($binding) {
                return is_numeric($binding)
                    ? $binding
                    : "'" . addslashes($binding) . "'";
            }, $bindings);

            return sprintf(str_replace('?', '%s', $sql), ...$bindings);
        });

        EloquentBuilder::macro('toRawSql', function () {
            /**
             * @var $this EloquentBuilder
             */
            return $this->toBase()->toRawSql();
        });
    }
}
