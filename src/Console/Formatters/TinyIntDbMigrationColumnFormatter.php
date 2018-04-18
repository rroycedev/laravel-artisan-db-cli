<?php

namespace Roycedev\DbCli\Console\Formatters;

class TinyIntDbMigrationColumnFormatter extends IntDbMigrationColumnFormatter
{
    /**
     * $typeMethodName
     *
     * @var string
     */
    protected $typeMethodName = "tinyInteger";

    /**
     * Returns the Laravel database migration script string that represents
     * a tinyint column
     *
     * @return string
     */
    public function toText()
    {
        if ($this->column->autoIncrement) {
            $this->typeMethodName = "tinyIncrements";
        }

        return parent::toText();
    }
}
