<?php

namespace Roycedev\DbCli;

use Roycedev\DbCli\Schema\TableInterface;

interface SchemaInterface
{
    /**
     * Add a new table to the database
     * @param TableInterface $table
     * @return TableInterface
     */
    public function add(TableInterface $table);

    /**
     * return the number of tables added by add()
     * @return integer
     */
    public function getTableCount();

    /**
     * @brief return the table
     * @param string $name of the table to return
     * @return TableInterface table in the database
     */
    public function getTable($name);

    /**
     * @brief return all the tables indexed by name
     * @return TableInterface[] table in the database
     */
    public function getTables();

    /**
     * names of all the tables
     * @return string[] keyed by name
     */
    public function getTableNames();

    /**
     * implements array iteration over tables
     * @return \ArrayIterator
     */
    public function getIterator();
}
