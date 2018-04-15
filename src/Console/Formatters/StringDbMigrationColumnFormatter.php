<?php

namespace Roycedev\DbCli\Console\Formatters;

use Roycedev\DbCli\Console\DbMigrationColumnFormatter;

abstract class StringDbMigrationColumnFormatter extends DbMigrationColumnFormatter
{
    protected $typeMethodName = "";

    public function toText()
    {
        $txt = '            $table->' . $this->typeMethodName . '(\'' . $this->column->colName . '\', ' . $this->column->length . ')';

        if ($this->column->allowNulls) {
            $txt .= "\n" . "                ->nullable()";
        }

        if ($this->column->charset != "") {
            $txt .= "\n" . '                ->charset(\'' . $this->column->charset . '\')';
        }

        if ($this->column->collation != "") {
            $txt .= "\n" . '                ->collation(\'' . $this->column->collation . '\')';
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
