<?php
return [
    "createtable" => '/\s*create\s+table\s+([^\s+]*)\s+\((.*)\s*\n\s*\)\s*(.*)\s*$/ims',
    'createtableifnotexists' => '/\s*create\s+table\s+if\s+not\s+exists\s+([^\s+]*)\s+\((.*)\s*\n\s*\)\s*(.*)\s*$/ims',
    "charset" => '/\s*(*:DEFAULT) CHAR(*:ACTER )SET\s*(*:=)\s*([^\s+]*)(.*)/',
    "collate" => '/\s*(*:DEFAULT) COLLATE\s*(*:=)\s*(.*)/',
    'engine' => '/ENGINE\s*=\s*([^\s]+)/',
];
