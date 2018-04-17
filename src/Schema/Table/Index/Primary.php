<?php

namespace Roycedev\DbCli\Schema\Table\Index;

use Roycedev\DbCli\Schema;
use Roycedev\DbCli\Schema\Table\Index;

/**
 * Class Primary
 */
class Primary extends Index
{
    public function __construct($columns = [], $type = 'primary')
    {
        parent::__construct('PRIMARY', $columns, $type);
    }
}
