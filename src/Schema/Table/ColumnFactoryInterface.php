<?php

namespace Roycedev\DbCli\Schema\Table;

/**
 * Class ColumnFactory
 */
interface ColumnFactoryInterface
{
    /**
     * @param string $name        sql column name
     * @param string $description textual description for comment field of column
     * @param string $sqlType     eg varchar(9)
     * @return Column
     */
    public function create($name, $description, $sqlType);
}
