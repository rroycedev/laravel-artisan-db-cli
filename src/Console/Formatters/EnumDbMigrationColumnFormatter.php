<?php

namespace Roycedev\DbCli\Console\Formatters;

use Roycedev\DbCli\Console\DbMigrationColumnFormatter;

class EnumDbMigrationColumnFormatter extends DbMigrationColumnFormatter
{
    /**
     * $typeMethodName
     *
     * @var string
     */
    protected $typeMethodName = "enum";

    /**
     * Returns the Laravel database migration script string that represents
     * a enum column
     *
     * @return string
     */
    public function toText()
    {
        $txt = '            $table->' . $this->typeMethodName . '(\'' . $this->column->colName . '\', [' . $this->column->enumValues . '])';

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
