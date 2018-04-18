<?php

namespace Roycedev\DbCli\Console;

/**
 * CommandHelper
 * Insert description here
 *
 * @category
 * @package
 * @author
 * @copyright
 * @license
 * @version
 * @link
 * @see
 * @since
 */
class CommandHelper
{
    /**
     * Returns true/false if the data type supports auto increment
     *
     * @param $dataType
     *
     * @return boolean
     */
    public static function dataTypeSupportsAutoIncrement($dataType)
    {
        switch ($dataType) {
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'bigint':
                return true;
            default:
                return false;
        }
    }

    /**
     * Returns true/false if the data type supports the length attribute
     *
     * @param $dataType
     *
     * @return boolean
     */
    public static function dataTypeSupportsLength($dataType)
    {
        echo "Checking data type [$dataType] for support of length\n";

        switch ($dataType) {
            case 'bit':
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'bigint':
            case 'real':
            case 'float':
            case 'double':
            case 'decimal':
            case 'numeric':
            case 'char':
            case 'varchar':
            case 'binary':
            case 'varbinary':
            case 'blob':
                return true;
            default:
                return false;
        }
    }

    /**
     * Returns true/false if the data type supports decimal places
     *
     * @param $dataType
     *
     * @return boolean
     */
    public static function dataTypeSupportsDecimalPlaces($dataType)
    {
        switch ($dataType) {
            case 'real':
            case 'float':
            case 'double':
            case 'decimal':
            case 'numeric':
                return true;
            default:
                return false;
        }
    }

    /**
     * Returns true/false if the data type supports unsigned
     *
     * @param $dataType
     *
     * @return boolean
     */
    public static function dataTypeSupportsUnsigned($dataType)
    {
        switch ($dataType) {
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'bigint':
            case 'real':
            case 'double':
            case 'float':
            case 'decimal':
            case 'numeric':
                return true;
            default:
                return false;
        }
    }

    /**
     * Returns true/false if the data type supports charset
     *
     * @param $dataType
     *
     * @return boolean
     */
    public static function dataTypeSupportsCharset($dataType)
    {
        switch ($dataType) {
            case 'char':
            case 'varchar':
            case 'tinytext':
            case 'mediumtext':
            case 'text':
            case 'longtext':
            case 'enum':
                return true;
            default:
                return false;
        }
    }
}
