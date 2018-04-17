<?php
return [
    "test" => [
        "varchar" => '/(.*)varchar\s*\((\d+)\)(.*)/ims',
        "createrow" => '/[\r\n]+\s*/m',
        "column" => '/^(.*)([^\s]+)\s+(.*)\s*/',
    ],
    "table" => [
        "createtable" => '/\s*create\s+table\s+([^\s+]*)\s+\((.*)\s*\n\s*\)\s*(.*)\s*$/ims',
        'createtableifnotexists' => '/\s*create\s+table\s+if\s+not\s+exists\s+([^\s+]*)\s+\((.*)\s*\n\s*\)\s*(.*)\s*$/ims',
        "charset" => '/\s*(*:DEFAULT) CHAR(*:ACTER )SET\s*(*:=)\s*([^\s+]*)(.*)/',
        "collate" => '/\s*(*:DEFAULT) COLLATE\s*(*:=)([^\s+]*)/',
        'engine' => '/ENGINE\s*=\s*([^\s]+)/',
        'objecttype' => '/[\r\n]+\s*/m',
        "comment" => '/\s+comment\s*\'.*\'/im',
        "primarykey" => '/^PRIMARY\s+KEY\s+\(\s*(.*)\s*\)/im',
        "uniquekey" => '/^UNIQUE KEY\s+(.*)\s+\(\s*([^\s]+)(?:\s*,\s*([^\s]+))*\s*\)/im',
        "fulltext" => '/^FULLTEXT KEY\s+(.*)\s+\(\s*([^\s]+)(?:\s*,\s*([^\s]+))*\s*\)/im',
        "column" => '/^([^\s]+)\s+(.*)\s*/',
        "foreignkey" => '/FOREIGN KEY\s(.*)\(([^)]+)\)\s+REFERENCES+(.*)\(([^)]+)\)+(.*)/',
        "key" => '/^KEY\s+(.*)\s+\(\s*([^\s]+)(?:\s*,\s*([^\s]+))*\s*\)/im',
    ],
    "column" => [
        '([a-z]*)int\(([\d]+)\) unsigned not null auto_increment' => '\Roycedev\DbCli\Schema\Table\Column\UnsignedPrimaryKeyColumn',
        '([a-z]*)int\(([\d]+)\) not null auto_increment' => '\Roycedev\DbCli\Schema\Table\Column\PrimaryKeyColumn',
        'tinyint\(([\d]+)\) unsigned' => '\Roycedev\DbCli\Schema\Table\Column\UnsignedTinyIntegerColumn',
        'tinyint\(([\d]+)\)' => '\Roycedev\DbCli\Schema\Table\Column\TinyIntegerColumn',
        'bit\(([\d]+)\) unsigned' => '\Roycedev\DbCli\Schema\Table\Column\UnsignedBitIntegerColumn',
        'bit\(([\d]+)\)' => '\Roycedev\DbCli\Schema\Table\Column\BitIntegerColumn',
        '([a-z]*)int\(([\d]+)\) unsigned' => '\Roycedev\DbCli\Schema\Table\Column\UnsignedIntegerColumn',
        '([a-z]*)int\(([\d]+)\)' => '\Roycedev\DbCli\Schema\Table\Column\IntegerColumn',
        '([a-z]*)text' => '\Roycedev\DbCli\Schema\Table\Column\TextColumn',
        '([a-z]*)blob' => '\Roycedev\DbCli\Schema\Table\Column\BlobColumn',
        'json' => '\Roycedev\DbCli\Schema\Table\Column\JsonColumn',
        'datetime' => '\Roycedev\DbCli\Schema\Table\Column\DateTimeColumn',
        'date' => '\Roycedev\DbCli\Schema\Table\Column\DateColumn',
        'timestamp' => '\Roycedev\DbCli\Schema\Table\Column\TimestampColumn',
        'varchar\s*\((\d+)\)(.*)' => '\Roycedev\DbCli\Schema\Table\Column\StringColumn',
        'char\s*\((\d+)\)(.*)' => '\Roycedev\DbCli\Schema\Table\Column\CharColumn',
//        'varchar\s*\((\d+)\)' => '\Roycedev\DbCli\Schema\Table\Column\StringColumn',
        'enum\s*\(\s*([^\)]*)\s*\) ' => '\Roycedev\DbCli\Schema\Table\Column\EnumerationColumn',
        'set\s*\(\s*(.*)\s*\) ' => '\Roycedev\DbCli\Schema\Table\Column\SetColumn',
        'float\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)' => '\Roycedev\DbCli\Schema\Table\Column\FloatColumn',
        'decimal\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)' => '\Roycedev\DbCli\Schema\Table\Column\DecimalColumn',
        'double\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)' => '\Roycedev\DbCli\Schema\Table\Column\DoubleColumn',
    ],
];
