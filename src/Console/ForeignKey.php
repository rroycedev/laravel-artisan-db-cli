<?php

namespace Roycedev\DbCli\Console;

class ForeignKey
{
    /**
     * Foreign key name
     *
     * @var string
     */
    public $fkName = "";
    /**
     * Foreign key columns
     *
     * @var array
     */
    public $fkColumns = array();
    /**
     * Parent table name
     *
     * @var string
     */
    public $parentTableName = "";
    /**
     * Parent table columns
     *
     * @var array
     */
    public $parentTableColumns = array();

    /**
     * Create new instance of ForeignKey
     *
     * @param string $fkName
     * @param array $fkColumns
     * @param string $parentTableName
     * @param array $parentTableColumns
     *
     * @return void
     */
    public function __construct($fkName, $fkColumns, $parentTableName, $parentTableColumns)
    {
        $this->fkName = $fkName;
        $this->fkColumns = $fkColumns;
        $this->parentTableName = $parentTableName;
        $this->parentTableColumns = $parentTableColumns;
    }

    /**
     * Returns the laravel artisan command text
     *
     *
     * @return string
     */
    public function toText()
    {
        $fkColumnList = $this->columnArrayToText($this->fkColumns);
        $parentTableColList = $this->columnArrayToText($this->parentTableColumns);

        $str = '            $table->foreign(' . $fkColumnList . ', \'' . $this->fkName . '\')->references(' .
        $parentTableColList . ')->on(\'' . $this->parentTableName . '\');';

        return $str;
    }

    /**
     * Returns the JSON representation of array of column names
     *
     * @param array $columns
     * @return string
     */
    private function columnArrayToText($columns)
    {
        $columnList = '[';

        $firstTime = true;

        foreach ($columns as $colName) {
            if (!$firstTime) {
                $columnList .= ", ";
            } else {
                $firstTime = false;
            }

            $columnList .= "'" . $colName . "'";
        }

        $columnList .= "]";

        return $columnList;
    }
}
