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
class DateColumn extends Column
{
    public function __construct($name, $description = '', $allowNull = false, $unique = false)
    {
        parent::__construct($name, $description, $allowNull, $unique);
    }

    public function getSQLType()
    {
        return 'date';
    }

    public function getDefaultValue()
    {
        return '0000-00-00';
    }

    public function getSQLDefault()
    {
        return 'default \'' . $this->getDefaultValue() . '\'';
    }
}
