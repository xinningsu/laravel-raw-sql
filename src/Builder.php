<?php

namespace Sulao\RawSql;

use DateTimeInterface;

/**
 * Class Builder
 *
 * @package Sulao\RawSql
 */
class Builder extends \Illuminate\Database\Query\Builder
{
    /**
     * Get the SQL representation or raw SQL of the query.
     *
     * @param bool $raw  whether return raw sql
     *
     * @return string
     */
    public function toSql($raw = false)
    {
        if ($raw === true) {
            return $this->toRawSql();
        }

        return parent::toSql();
    }

    /**
     * Get the raw SQL of the query.
     *
     * @return string
     */
    public function toRawSql()
    {
        $sql = $this->toSql();
        $bindings = $this->getBindings();

        foreach ($bindings as $key => $value) {
            if ($value instanceof DateTimeInterface) {
                $bindings[$key] = $value->format(
                    $this->grammar->getDateFormat()
                );
            } elseif (is_bool($value)) {
                $bindings[$key] = (int) $value;
            }
        }

        $bindings = array_map(function ($binding) {
            return is_numeric($binding)
                ? $binding
                : "'" . addslashes($binding) . "'";
        }, $bindings);

        return sprintf(str_replace('?', '%s', $sql), ...$bindings);
    }
}
