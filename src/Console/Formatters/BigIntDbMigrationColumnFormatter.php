<?php

namespace Roycedev\DbCli\Console\Formatters;

class BigIntDbMigrationColumnFormatter extends IntDbMigrationColumnFormatter
{
    protected $typeMethodName = "bigInteger";

    public function toText()
    {
        if ($this->column->autoIncrement) {
            $this->typeMethodName = "bigIncrements";
        }

        return parent::toText();
    }
}
