<?php

namespace Roycedev\DbCli\Console\Formatters;

class LongTextDbMigrationColumnFormatter extends TextDbMigrationColumnFormatter
{
    /**
     * $typeMethodName
     *
     * @var string
     */
    protected $typeMethodName = "longText";
    /**
     * $wantLength
     *
     * @var boolean
     */
    protected $wantLength = false;
}
