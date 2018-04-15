<?php

namespace Roycedev\DbCli\Console;

class ForeignKey
{
    public $fkName;
    public $fkColumns;
    public $parentTableName;
    public $parentTableColumns;

    public function __construct($fkName, $fkColumns, $parentTableName, $parentTableColumns)
    {
        $this->fkName = $fkName;
        $this->fkColumns = $fkColumns;
        $this->parentTableName = $parentTableName;
        $this->parentTableColumns = $parentTableColumns;
    }
}
