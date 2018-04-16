<?php

namespace Roycedev\DbCli\Console;

class CommandHelper
{
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

