<?php

namespace Roycedev\DbCli\Console\Formatters;

class MediumTextDbMigrationColumnFormatter extends TextDbMigrationColumnFormatter
{
    protected $typeMethodName = "mediumText";
    protected $wantLength = false;
}
