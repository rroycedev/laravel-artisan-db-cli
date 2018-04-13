<?php
/**
 * @file
 * @author  Lightly Salted Software Ltd
 * @date    March 2015
 */

namespace Roycedev\DbCli\Schema\Table\Column;

use Roycedev\DbCli\Schema;
use Roycedev\DbCli\Schema\Table\Column;

/**
 */
class EnumerationColumn extends Column
{
    protected $type = 'enum';
    /** @var array string */
    private $values = [];

    public function __construct($name, $description = '', $allowNull = false, $unique = false, $values = '')
    {
        $this->parseValues($values);
        parent::__construct($name, $description, $allowNull, $unique);
    }

    /**
     * @param string[] | string $values
     */
    public function parseValues($values)
    {
        if (!is_array($values)) {
            $values = array_map('trim', explode(',', $values));
            $values = array_map('Roycedev\DbCli\Schema::unQuote', $values);
        }
        foreach ($values as $value) {
            $this->values[$value] = $value;
        }
    }

    public function getSQLType()
    {
        return $this->type . ' (' . join(',', array_map('Roycedev\DbCli\Schema::quoteEnumValue', $this->values)) . ')';
    }

    public function getDefaultValue()
    {
        $default = array_values($this->values);
        return $default[0];
    }

    public function getSQLDefault()
    {
        return 'default ' . Schema::quoteEnumValue($this->getDefaultValue());
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
