<?php

namespace Sulao\RawSql;

/**
 * Class SqlServerConnection
 *
 * @package Sulao\RawSql
 */
class SqlServerConnection extends \Illuminate\Database\SqlServerConnection
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
