<?php

namespace Sulao\RawSql;

/**
 * Class MySqlConnection
 *
 * @package Sulao\RawSql
 */
class MySqlConnection extends \Illuminate\Database\MySqlConnection
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
