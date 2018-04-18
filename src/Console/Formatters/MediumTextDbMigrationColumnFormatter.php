<?php

namespace Roycedev\DbCli\Console\Formatters;

class MediumTextDbMigrationColumnFormatter extends TextDbMigrationColumnFormatter
{
    /**
     * $typeMethodName
     *
     * @var string
     */
    protected $typeMethodName = "mediumText";
    /**
     * $wantLength
     *
     * @var boolean
     */
    protected $wantLength = false;
}
