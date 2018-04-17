<?php

namespace Roycedev\DbCli\Console;

class Index
{
    public $indexName = "";
    public $indexColumns = array();
    public $indexType = "";

    public function __construct($indexName, $indexColumns, $indexType)
    {
        $this->indexName = $indexName;
        $this->indexColumns = $indexColumns;
        $this->indexType = $indexType;
    }

    public function toText()
    {
        $columnList = '[';

        $firstTime = true;

        foreach ($this->indexColumns as $colName) {
            if (!$firstTime) {
                $columnList .= ", ";
            } else {
                $firstTime = false;
            }

            $columnList .= "'" . $colName . "'";
        }

        $columnList .= "]";

        $methodName = "";

        switch ($this->indexType) {
            case 'Primary':
                $methodName = 'primary';
                break;
            case 'Unique':
                $methodName = 'unique';
                break;
            case 'Non-Unique':
                $methodName = 'index';
                break;
            default:
                throw new \Exception("Invalid index type  '" . $this->indexType . "'");
        }

        return '            $table->' . $methodName . '(' . $columnList . ', \'' . $this->indexName . '\');';
    }
}
