<?php

namespace Roycedev\DbCli\Console\Formatters;

class BigIntDbMigrationColumnFormatter extends IntDbMigrationColumnFormatter
{
    /**
     * $typeMethodName
     *
     * @var string
     */
    protected $typeMethodName = "bigInteger";

    /**
     * Returns the Laravel database migration script string that represents
     * a bigint column
     *
     * @return string
     */
    public function toText()
    {
        if ($this->column->autoIncrement) {
            $this->typeMethodName = "bigIncrements";
        }

        return parent::toText();
    }
}
