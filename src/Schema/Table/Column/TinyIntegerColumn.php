<?php

namespace Roycedev\DbCli\Schema\Table\Column;

/**
 */
class TinyIntegerColumn extends IntegerColumn
{
    const BOOLEAN_SIZE = 'tiny';
    const BOOLEAN_DIGITS = 4;

    public function __construct($name, $description = '', $allowNull = false, $unique = false, $charset = "", $collate = "", $digits = self::BOOLEAN_DIGITS)
    {
        $allowNull = false;
        parent::__construct($name, $description, $allowNull, $unique, $charset, $collate, self::BOOLEAN_SIZE, $digits);
    }

    public function getDefaultValue()
    {
        return 0;
    }
}
