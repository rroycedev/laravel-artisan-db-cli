<?php

namespace Roycedev\DbCli\Console\Formatters;

class LongTextDbMigrationColumnFormatter extends TextDbMigrationColumnFormatter
{
    protected $typeMethodName = "longText";
    protected $wantLength = false;
}
