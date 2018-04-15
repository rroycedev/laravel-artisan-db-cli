<?php

namespace Roycedev\DbCli\Console\Formatters;

use Roycedev\DbCli\Console\DbMigrationColumnFormatter;

class DateDbMigrationColumnFormatter extends DbMigrationColumnFormatter
{
    protected $typeMethodName = "date";

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
