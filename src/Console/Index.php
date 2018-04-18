<?php

namespace Roycedev\DbCli\Console;

class Index
{
    /**
     * $indexName Index name
     *
     * @var string
     */
    public $indexName = "";
    /**
     * $indexColumns Index columns
     *
     * @var array
     */
    public $indexColumns = [];
    /**
     * $indexType Index type ('primary', 'unique', 'index')
     *
     * @var string
     */
    public $indexType = "";

    /**
     * Creates a new instance of Index
     *
     * @param string $indexName     Index name
     * @param array  $indexColumns  Index columns
     * @param string $indexType     Index type (primary, unique, index)
     */
    public function __construct($indexName, $indexColumns, $indexType)
    {
        $this->indexName = $indexName;
        $this->indexColumns = $indexColumns;
        $this->indexType = $indexType;
    }

    /**
     * Returns the Laravel artisan command text
     *
     * @return string
     */
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
