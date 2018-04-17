<?php
/**
 * @file
 * @author  Lightly Salted Software Ltd
 * @date    March 2015
 */

namespace Roycedev\DbCli\Schema\Table\Column;

/**
 */
class CharColumn extends StringColumn
{
    public function getSQLType()
    {
        return 'char(' . $this->length . ')';
    }

    public function getSQLDefault()
    {
        return 'default \'\'';
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }
}

