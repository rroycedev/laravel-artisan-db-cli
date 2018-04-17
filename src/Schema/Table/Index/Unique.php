<?php

namespace Roycedev\DbCli\Schema\Table\Index;

use Roycedev\DbCli\Schema;
use Roycedev\DbCli\Schema\Table\Index;

/**
 * Class Column
 */
class Unique extends Index
{
    public function __construct($name, $columns = [], $type = 'unique')
    {
        parent::__construct($name, $columns, $type);
    }
}
