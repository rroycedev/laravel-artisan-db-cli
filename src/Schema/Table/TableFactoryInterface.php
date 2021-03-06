<?php

namespace Roycedev\DbCli\Schema\Table;

use Roycedev\DbCli\Schema\TableInterface;

/**
 * Class TableFactory
 */
interface TableFactoryInterface
{
    /**
     * @param string $name name of the table
     * @param string $description
     * @return TableInterface
     */
    public function createTable($name, $description = '');
}
