<?php

namespace Roycedev\DbCli\Console;

class Index
{
    public $indexName;
    public $indexColumns;
    public $unique;

    public function __construct($indexName, $indexColumns, $unique)
    {
        $this->indexName = $indexName;
        $this->indexColumns = $indexColumns;
        $this->unique = $unique;
    }
}
