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

    public function toText()
    {
        $fkColumnList = $this->columnArrayToText($this->fkColumns);
        $parentTableColList = $this->columnArrayToText($this->parentTableColumns);

        $str = '            $table->foreign(' . $fkColumnList . ', \'' . $this->fkName . '\')->references(' .
        $parentTableColList . ')->on(\'' . $this->parentTableName . '\');';

        return $str;
    }

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
