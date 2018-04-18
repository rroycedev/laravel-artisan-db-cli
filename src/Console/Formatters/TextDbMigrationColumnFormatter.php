<?php

namespace Roycedev\DbCli\Console\Formatters;

use Roycedev\DbCli\Console\DbMigrationColumnFormatter;

class TextDbMigrationColumnFormatter extends DbMigrationColumnFormatter
{
    /**
     * $typeMethodName
     *
     * @var string
     */
    protected $typeMethodName = "text";
    /**
     * $wantLength
     *
     * @var boolean
     */
    protected $wantLength = true;

    /**
     * Returns the Laravel database migration script string that represents
     * a text column
     *
     * @return string
     */
    public function toText()
    {
        $txt = '            $table->' . $this->typeMethodName . '(\'' . $this->column->colName . '\'';

        if ($this->wantLength) {
            $txt .= ', ' . $this->column->length;
        }

        $txt .= ')';

        if ($this->column->allowNulls) {
            $txt .= "\n" . "                ->nullable()";
        }

        if ($this->column->comment != "") {
            $txt .= "\n" . '                ->comment(\'' . $this->column->comment . '\')';
        }

        if ($this->column->defaultValue != "") {
            $txt .= "\n" . '                ->default(\'' . $this->column->defaultValue . '\')';
        }

        $txt .= ";";

        return $txt;
    }
}
