<?php

namespace Roycedev\DbCli\Console\Formatters;

class TinyIntDbMigrationColumnFormatter extends IntDbMigrationColumnFormatter
{
    protected $typeMethodName = "tinyInteger";

    public function toText()
    {
        if ($this->column->autoIncrement) {
            $this->typeMethodName = "tinyIncrements";
        }

        return parent::toText();
    }

}
