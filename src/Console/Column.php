<?php

namespace Roycedev\DbCli\Console;

/**
 * Column
 * Insert description here.
 *
 * @author
 * @copyright
 * @license
 *
 * @version
 *
 * @see
 * @see
 * @since
 */
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

    /**
     * Create a new Column instance
     *
     * @param $colName
     * @param $dataType
     * @param $length
     * @param $decimalPlaces
     * @param $unsigned
     * @param $allowNulls
     * @param $charset
     * @param $collation
     * @param $autoIncrement
     * @param $defaultValue
     * @param $comment
     *
     * @return
     */
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
