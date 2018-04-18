<?php

namespace Roycedev\DbCli\Console\Formatters;

use Roycedev\DbCli\Console\DbMigrationColumnFormatter;

class DateDbMigrationColumnFormatter extends DbMigrationColumnFormatter
{
    /**
     * $typeMethodName
     *
     * @var string
     */
    protected $typeMethodName = "date";

    /**
     * Returns the Laravel database migration script string that represents
     * a date column
     *
     * @return string
     */
    public function toText()
    {
        $txt = '            $table->' . $this->typeMethodName . '(\'' . $this->column->colName . '\')';

        if ($this->column->allowNulls) {
            $txt .= "\n" . "                ->nullable()";
        }

        if ($this->column->comment != "") {
            $txt .= "\n" . '                ->comment(\'' . $this->column->comment . '\')';
        }

        if ($this->column->defaultValue != "" && strtoupper($this->column->defaultValue) == "CURRENT_TIMESTAMP") {
            $txt .= "\n" . '                ->useCurrent()';
        }

        $txt .= ";";

        return $txt;
    }
}
