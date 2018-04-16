<?php

namespace Roycedev\DbCli\Console\Formatters;

class VarcharDbMigrationColumnFormatter extends StringDbMigrationColumnFormatter
{
    protected $typeMethodName = "string";
}
