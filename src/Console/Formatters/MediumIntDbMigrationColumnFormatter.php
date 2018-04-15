<?php

namespace Roycedev\DbCli\Console\Formatters;

class MediumIntDbMigrationColumnFormatter extends IntDbMigrationColumnFormatter
{
    protected $typeMethodName = "mediumInteger";

    public function toText()
    {
        if ($this->column->autoIncrement) {
            $this->typeMethodName = "mediumIncrements";
        }

        return parent::toText();
    }

}
