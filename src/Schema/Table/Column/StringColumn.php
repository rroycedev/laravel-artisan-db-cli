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
class StringColumn extends Column
{
    const DEFAULT_LENGTH = 50;

    /** @var integer  */
    private $length = self::DEFAULT_LENGTH;


    public function __construct($name, $description = '', $allowNull = false, $unique = false, $length = self::DEFAULT_LENGTH)
    {
        $this->length = $length;
        parent::__construct($name, $description, $allowNull, $unique);
    }


    public function getSQLType()
    {
        return 'varchar(' . $this->length . ')';
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
