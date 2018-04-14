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
class DateTimeColumn extends Column
{
    public function __construct($name, $description = '', $allowNull = false, $unique = false, $charset = "", $collate = "")
    {
        parent::__construct($name, $description, $allowNull, $unique, $charset, $collate);
    }

    public function getSQLType()
    {
        return 'datetime';
    }

    public function getDefaultValue()
    {
        return '0000-00-00 00:00:00';
    }

    public function getSQLDefault()
    {
        return 'default \'' . $this->getDefaultValue() . '\'';
    }
}
