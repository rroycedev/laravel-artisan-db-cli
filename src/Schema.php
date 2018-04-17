<?php

namespace Roycedev\DbCli;

use Roycedev\DbCli\Schema\TableInterface;

class Schema implements \IteratorAggregate, SchemaInterface
{
    const BACKTICK = '`';
    const QUOTE = "'";

    /** @var TableInterface[] */
    private $table = [];

    /**
     * Add a new table to the database
     * @param TableInterface $table
     * @return TableInterface
     */
    public function add(TableInterface $table)
    {
        return $this->table[$table->getName()] = $table;
    }

    /**
     * return the number of tables added by add()
     * @return integer
     */
    public function getTableCount()
    {
        return count($this->table);
    }

    /**
     * @brief return the table
     * @param string $name of the table to return
     * @return TableInterface table in the database
     */
    public function getTable($name)
    {
        if (!isset($this->table[$name])) {
            throw new \InvalidArgumentException('Unknown table ' . $name);
        }

        return $this->table[$name];
    }

    /**
     * @brief return the full set of tables
     * @return TableInterface[] keyed on table name
     */
    public function getTables()
    {
        return $this->table;
    }

    /**
     * names of all the tables
     * @return string[]
     */
    public function getTableNames()
    {
        return array_keys($this->table);
    }

    /**
     * implements array iteration over tables
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->table);
    }

    /**
     * return the $id quoted with BACKTICK
     * @param string $id the id to quote
     * @return string column name (quoted)
     */
    public static function quoteIdentifier($id)
    {
        if (empty($id)) {
            return '';
        }

        return self::BACKTICK . $id . self::BACKTICK;
    }

    /**
     * @param string $text
     * @return string quoted $text
     */
    public static function quoteDescription($text)
    {
        return self::QUOTE . str_replace(self::QUOTE, self::QUOTE . self::QUOTE, $text) . self::QUOTE;
    }

    /**
     * @param string $text
     * @return string quoted $text
     */
    public static function quoteEnumValue($text)
    {
        return self::quoteDescription($text);
    }

    /**
     * @param string $value
     * @return string
     */
    public static function unQuote($value)
    {
        if (substr($value, 0, 1) == substr($value, -1) && substr($value, 0, 1) == '"') {
            $value = substr($value, 1, -1);
        }
        if (substr($value, 0, 1) == substr($value, -1) && substr($value, 0, 1) == self::BACKTICK) {
            $value = substr($value, 1, -1);
        }
        if (substr($value, 0, 1) == substr($value, -1) && substr($value, 0, 1) == "'") {
            $value = substr($value, 1, -1);
        }
        $value = str_replace("''", "'", $value);
        $value = str_replace("`", "", $value);

        return trim($value);
    }
}
