<?php

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
