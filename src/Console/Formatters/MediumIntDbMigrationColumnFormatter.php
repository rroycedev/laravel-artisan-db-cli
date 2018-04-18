<?php

namespace Roycedev\DbCli\Console\Formatters;

class MediumIntDbMigrationColumnFormatter extends IntDbMigrationColumnFormatter
{
    /**
     * $typeMethodName
     *
     * @var string
     */
    protected $typeMethodName = "mediumInteger";

    /**
     * Returns the Laravel database migration script string that represents
     * a mediumint column
     *
     * @return string
     */
    public function toText()
    {
        if ($this->column->autoIncrement) {
            $this->typeMethodName = "mediumIncrements";
        }

        return parent::toText();
    }
}
