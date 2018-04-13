<?php
/**
 * @file
 * @author  Lightly Salted Software Ltd
 * @date    March 2015
 */

namespace Roycedev\DbCli\Schema\Table;

/**
 * Class ColumnFactory
 */
class ColumnFactory implements ColumnFactoryInterface
{
    // maps sql type regex to class names
    private $map = [
        '([a-z]*)int\(([\d]+)\) unsigned not null auto_increment' => '\Roycedev\DbCli\Schema\Table\Column\UnsignedPrimaryKeyColumn',
        '([a-z]*)int\(([\d]+)\) not null auto_increment' => '\Roycedev\DbCli\Schema\Table\Column\PrimaryKeyColumn',
        'tinyint\(([\d]+)\) unsigned'                      => '\Roycedev\DbCli\Schema\Table\Column\UnsignedTinyIntegerColumn',
        'tinyint\(([\d]+)\)'                              => '\Roycedev\DbCli\Schema\Table\Column\TinyIntegerColumn',
        'bit\(([\d]+)\) unsigned'                      => '\Roycedev\DbCli\Schema\Table\Column\UnsignedBitIntegerColumn',
        'bit\(([\d]+)\)'                              => '\Roycedev\DbCli\Schema\Table\Column\BitIntegerColumn',
        '([a-z]*)int\(([\d]+)\) unsigned'                => '\Roycedev\DbCli\Schema\Table\Column\UnsignedIntegerColumn',
        '([a-z]*)int\(([\d]+)\)'                         => '\Roycedev\DbCli\Schema\Table\Column\IntegerColumn',
        '([a-z]*)text'                                   => '\Roycedev\DbCli\Schema\Table\Column\TextColumn',
        '([a-z]*)blob'                                   => '\Roycedev\DbCli\Schema\Table\Column\BlobColumn',
        'json'                                            => '\Roycedev\DbCli\Schema\Table\Column\JsonColumn',
        'datetime'                                        => '\Roycedev\DbCli\Schema\Table\Column\DateTimeColumn',
        'date'                                            => '\Roycedev\DbCli\Schema\Table\Column\DateColumn',
        'varchar\s*\((\d+)\)'                             => '\Roycedev\DbCli\Schema\Table\Column\StringColumn',
        'enum\s*\(\s*([^\)]*)\s*\) '                      => '\Roycedev\DbCli\Schema\Table\Column\EnumerationColumn',
        'set\s*\(\s*(.*)\s*\) '                           => '\Roycedev\DbCli\Schema\Table\Column\SetColumn',
        'float\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)'           => '\Roycedev\DbCli\Schema\Table\Column\FloatColumn',
        'decimal\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)'           => '\Roycedev\DbCli\Schema\Table\Column\DecimalColumn',
        'double\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)'           => '\Roycedev\DbCli\Schema\Table\Column\DoubleColumn',
    ];


    /**
     *
     * @param string $sqlType regular expression
     * @param string $className
     * @return $this
     */
    public function setMapType($sqlType, $className)
    {
        $this->map[strtolower($sqlType)] = $className;

        return $this;
    }


    /**
     * @param string $name        sql column name
     * @param string $description textual description for comment field of column
     * @param string $sqlType     eg varchar(9)
     * @return Column
     */
    public function create($name, $description, $sqlType)
    {
        foreach ($this->map as $regex => $className) {
            if (!preg_match('/^' . $regex . '/i', $sqlType, $matches)) {
                continue;
            }

	echo "Checking sqlType [$sqlType]\n";
	    echo "Found class $className:\n";
		print_r($matches);

            $allowNull = stripos($sqlType, ' not null') === false;
	    $unique = stripos($sqlType, " unique") !== false;

            $instance = new $className($name, $description, $allowNull, $unique,
                isset($matches[1]) ? $matches[1] : null,
                isset($matches[2]) ? $matches[2] : null
            );

            return $instance;
        }
        throw new \InvalidArgumentException('Unknown SQL field type "' . $sqlType . '" for field ' . $name);
    }
}
