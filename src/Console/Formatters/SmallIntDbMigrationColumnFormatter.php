<?php

namespace Roycedev\DbCli\Console\Formatters;

class SmallIntDbMigrationColumnFormatter extends IntDbMigrationColumnFormatter
{
    protected $typeMethodName = "smallInteger";

    public function toText()
    {
        if ($this->column->autoIncrement) {
            $this->typeMethodName = "smallIncrements";
        }

        return parent::toText();
    }
}
