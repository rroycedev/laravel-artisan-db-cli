<?php
/**
 * @file
 * @author  Lightly Salted Software Ltd
 * @date    March 2015
 */

namespace Roycedev\DbCli\Schema\Table;

use Roycedev\DbCli\Schema;

/**
 *
 */
class Index
{
    /** @var string */
    private $name;

    /** @var string[] */
    private $columns = [ ];

    /** @var string */
    private $type = '';


    /**
     * @param string   $name column name
     * @param string[] $columns
     * @param string   $type
     */
    public function __construct($name, $columns = [ ], $type = '')
    {
        if (empty($columns)) {
            // assume the index is named after the column it indexes
            $columns[]= $name;
        }
        $this->name    = $name;

	if (gettype($columns) == "string") {
		$columns = explode(",", $columns);
	}

        $this->columns = $columns;
        $this->type    = $type;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return string sql
     */
    public function toSQL()
    {
        return trim($this->type . ' key ' . Schema::quoteIdentifier($this->getName())
            . ' (' . join(',', array_map('Roycedev\DbCli\Schema::quoteIdentifier', $this->columns)) . ')');
    }


    /**
     * @return string[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    public function getType() {
	return $this->type;
    }
}
