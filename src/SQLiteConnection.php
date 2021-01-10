<?php

namespace Sulao\RawSql;

/**
 * Class SQLiteConnection
 *
 * @package Sulao\RawSql
 */
class SQLiteConnection extends \Illuminate\Database\SQLiteConnection
{
    /**
     * Get a new query builder instance.
     *
     * @return Builder
     */
    public function query()
    {
        return new Builder(
            $this,
            $this->getQueryGrammar(),
            $this->getPostProcessor()
        );
    }
}
