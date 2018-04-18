<?php

namespace Roycedev\DbCli\Console;

abstract class DbMigrationColumnFormatter
{
    protected $column;

    /**
     * Creates a new instance of DbMigrationColumnFormatted
     *
     * @param Column
     *
     * @return void
     */
    public function __construct(Column $column)
    {
        $this->column = $column;
    }
}
