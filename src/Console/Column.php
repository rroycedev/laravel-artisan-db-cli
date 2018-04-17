<?php

namespace Roycedev\DbCli\Console;

class Column
{
    public $colName;
    public $dataType;
    public $length;
    public $decimalPlaces;
    public $unsigned;
    public $allowNulls;
    public $charset;
    public $collation;
    public $autoIncrement;
    public $defaultValue;
    public $comment;

    public function __construct(
        $colName,
        $dataType,
        $length,
        $decimalPlaces,
        $unsigned,
        $allowNulls,
        $charset,
        $collation,
        $autoIncrement,
        $defaultValue,
        $comment
    ) {
        $this->colName = $colName;
        $this->dataType = $dataType;
        $this->length = $length;
        $this->decimalPlaces = $decimalPlaces;
        $this->unsigned = $unsigned;
        $this->allowNulls = $allowNulls;
        $this->charset = $charset;
        $this->collation = $collation;
        $this->autoIncrement = $autoIncrement;
        $this->defaultValue = $defaultValue;
        $this->comment = $comment;
    }
}
