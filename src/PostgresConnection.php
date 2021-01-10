<?php

namespace Sulao\RawSql;

/**
 * Class PostgresConnection
 *
 * @package Sulao\RawSql
 */
class PostgresConnection extends \Illuminate\Database\PostgresConnection
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
