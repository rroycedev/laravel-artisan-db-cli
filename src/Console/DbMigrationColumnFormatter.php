<?php

namespace Roycedev\DbCli\Console;

abstract class DbMigrationColumnFormatter
{
    protected $column;

    public function __construct(Column $column)
    {
        $this->column = $column;
    }
}
