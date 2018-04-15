<?php

namespace Roycedev\DbCli\Console\Formatters;

use Roycedev\DbCli\Console\DbMigrationColumnFormatter;

class IntDbMigrationColumnFormatter extends DbMigrationColumnFormatter
{
    protected $typeMethodName = "integer";

    public function toText()
    {
        if ($this->column->autoIncrement && $this->typeMethodName == "integer") {
            $this->typeMethodName = "increment";
        }

        $txt = '            $table->' . $this->typeMethodName . '(\'' . $this->column->colName . '\')';

        if ($this->column->allowNulls) {
            $txt .= "\n" . "                ->nullable()";
        }

        if ($this->column->unsigned) {
            $txt .= "\n" . "                ->unsigned()";
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
