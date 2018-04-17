<?php

namespace Roycedev\DbCli\Schema\Table;

use Roycedev\DbCli\Schema\Table;
use Roycedev\DbCli\Schema\TableInterface;

/**
 * Class TableFactory
 */
class TableFactory implements TableFactoryInterface
{
    /**
     * @param string $name name of the table
     * @param string $description
     * @return TableInterface
     */
    public function createTable($name, $description = '', $engine = "", $charset = "", $collate = "")
    {
        return new Table($name, $description, $engine, $charset, $collate);
    }
}
