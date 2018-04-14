<?php
/**
 * @file
 * @author  Lightly Salted Software Ltd
 * @date    March 2015
 */

namespace Roycedev\DbCli\Schema\Table\Column;

/**
 */
class BitIntegerColumn extends IntegerColumn
{
    const BOOLEAN_SIZE = 'tiny';
    const BOOLEAN_DIGITS = 1;

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
