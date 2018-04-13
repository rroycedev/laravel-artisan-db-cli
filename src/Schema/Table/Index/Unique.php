<?php
/**
 * @file
 * @author  Lightly Salted Software Ltd
 * @date    March 2015
 */

namespace Roycedev\DbCli\Schema\Table\Index;

use Roycedev\DbCli\Schema\Table\Index;
use Roycedev\DbCli\Schema;

/**
 * Class Column
 */
class Unique extends Index
{
    public function __construct($name, $columns = [ ], $type = 'unique')
    {
        parent::__construct($name, $columns, $type);
    }
}
