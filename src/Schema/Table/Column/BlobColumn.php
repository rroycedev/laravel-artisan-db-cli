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
class BlobColumn extends Column
{
    /** @var string  */
    private $size = ''; // tiny, medium, '' etc

    public function __construct($name, $description = '', $allowNull = false, $unique = false, $charset = "", $collate = "", $size = '')
    {
        $this->size = $size;
        parent::__construct($name, $description, $allowNull, $unique, $charset, $collate);
    }

    public function getSQLType()
    {
        return $this->size . 'blob';
    }

    public function getSQLDefault()
    {
        if ($this->isAllowedNull()) {
            return 'default null';
        }
        return 'default \'\'';
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }
}
