<?php
/**
 * @file
 * @author  Lightly Salted Software Ltd
 * @date    March 2015
 */

namespace Roycedev\DbCli\Schema\Table\Column;

use Roycedev\DbCli\Schema\Table\Column;


/**
 */
class UnsignedPrimaryKeyColumn extends IntegerColumn
{
    const DEFAULT_SIZE = 'medium';
    const DEFAULT_DIGITS = 9;

    public function __construct(
        $name,
        $description = '',
        $allowNull = false,
 	$unique = false,
        $size = self::DEFAULT_SIZE,
        $digits = self::DEFAULT_DIGITS
    ) {
        $allowNull = false;
        parent::__construct($name, $description, $allowNull, $unique, $size, $digits);
    }


    public function getSQLType()
    {
        return parent::getSQLType() . ' auto_increment';
    }


    public function getSQLDefault()
    {
        return '';
    }
}
