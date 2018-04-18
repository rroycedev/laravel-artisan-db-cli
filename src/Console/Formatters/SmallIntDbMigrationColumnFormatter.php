<?php

namespace Roycedev\DbCli\Console\Formatters;

class SmallIntDbMigrationColumnFormatter extends IntDbMigrationColumnFormatter
{
    /**
     * $typeMethodName
     *
     * @var string
     */
    protected $typeMethodName = "smallInteger";

    /**
     * Returns the Laravel database migration script string that represents
     * a smallint column
     *
     * @return string
     */
    public function toText()
    {
        if ($this->column->autoIncrement) {
            $this->typeMethodName = "smallIncrements";
        }

        return parent::toText();
    }
}
