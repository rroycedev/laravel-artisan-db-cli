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
class IntegerColumn extends Column
{
    const DEFAULT_DIGITS = 9;

    /** @var string */
    private $size = ''; // tiny, medium, '' etc

    /** @var int */
    private $digits = self::DEFAULT_DIGITS;

    private $unsigned = "";

    public function __construct(
        $name,
        $description = '',
        $allowNull = false,
 	$unique = false,
        $size = '',
        $digits = self::DEFAULT_DIGITS,
        $unsigned = ""
    ) {
        $this->size = $size;
        $this->digits = $digits;
	$this->unsigned = $unsigned;
        parent::__construct($name, $description, $allowNull, $unique);
    }

    public function getSQLType()
    {
        return $this->size . 'int(' . $this->digits . ')';
    }

    public function getDefaultValue()
    {
        return 0;
    }

    public function getSQLDefault()
    {
        return 'default \'' . $this->getDefaultValue() . '\'';
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getDigits()
    {
        return $this->digits;
    }

    public function getUnsigned() {
	return $this->unsigned;
    }
}
