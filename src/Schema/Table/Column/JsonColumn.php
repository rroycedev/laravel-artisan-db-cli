<?php

namespace Roycedev\DbCli\Schema\Table\Column;

use Roycedev\DbCli\Schema\Table\Column;

/**
 */
class JsonColumn extends Column
{
    public function __construct($name, $description = '', $allowNull = false, $unique = false, $charset = "", $collate = "")
    {
        parent::__construct($name, $description, $allowNull, $unique, $charset, $collate);
    }

    public function getSQLType()
    {
        return 'json';
    }
}
