<?php

namespace Roycedev\DbCli\Console;

use Illuminate\Console\Command;
use Roycedev\DbCli\Schema;
use Roycedev\DbCli\Schema\Parser;

class ImportSchemaObjectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbcli:importschema filename? : The name of the file that contains the DDL to convert to a database migration script.} {migrationname? : The name of the database migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates database migration scripts from a DDL script';

    /*
    Here is the mapping from MySQL 5.7 data types to Laravel database migration class methods:

    BIT[(length)]                                               $table->tinyInteger()
    TINYINT[(length)] [UNSIGNED] [ZEROFILL]                     $table->tinyInteger()
    SMALLINT[(length)] [UNSIGNED] [ZEROFILL]                    $table->smallInteger()
    MEDIUMINT[(length)] [UNSIGNED] [ZEROFILL]                   $table->mediumInteger()
    INT[(length)] [UNSIGNED] [ZEROFILL]                         $table->integer()
    INTEGER[(length)] [UNSIGNED] [ZEROFILL]                     $table->integer()
    BIGINT[(length)] [UNSIGNED] [ZEROFILL]                      $table->bigInteger()
    REAL[(length,decimals)] [UNSIGNED] [ZEROFILL]               $table->decimal()
    DOUBLE[(length,decimals)] [UNSIGNED] [ZEROFILL]             $table->double()
    FLOAT[(length,decimals)] [UNSIGNED] [ZEROFILL]              $table->float()
    DECIMAL[(length[,decimals])] [UNSIGNED] [ZEROFILL]          $table->decimal()
    NUMERIC[(length[,decimals])] [UNSIGNED] [ZEROFILL]          $table->decimal()
    DATE                                                        $table->date()
    TIME[(fsp)]                                                 $table->time()
    TIMESTAMP[(fsp)]                                            $table->timestamp()
    DATETIME[(fsp)]                                             $table->dateTime()
    YEAR                                                        $table->year()
    CHAR[(length)]                                              $table->char()
    VARCHAR(length)                                             $table->varchar()
    BINARY[(length)]                                            $table->binary()
    VARBINARY(length)                                           $table->binary()
    TINYBLOB                                                    $table->binary()
    BLOB[(length)]                                              $table->binary()
    MEDIUMBLOB                                                  $table->binary()
    LONGBLOB                                                    $table->binary()
    TINYTEXT                                                    $table->mediumText()
    TEXT[(length)]                                              $table->text()
    MEDIUMTEXT                                                  $table->mediumText()
    LONGTEXT                                                    $table->longText()
    ENUM(value1,value2,value3,...)                              $table->enum()
    JSON                                                        $table->json()

     */

    /**
     * The possible column data types a user can choose from
     *
     * @var string array
     */
    private $columnDataTypeMapping = [
        'bit' => 'BitDbMigrationColumnFormatter',
        'tinyint' => 'TinyIntDbMigrationColumnFormatter',
        'smallint' => 'SmallIntDbMigrationColumnFormatter',
        'mediumint' => 'MediumIntDbMigrationColumnFormatter',
        'int' => 'IntDbMigrationColumnFormatter',
        'bigint' => 'BigIntDbMigrationColumnFormatter',
        'real' => 'DecimalDbMigrationColumnFormatter',
        'double' => 'DoubleDbMigrationColumnFormatter',
        'float' => 'FloatDbMigrationColumnFormatter',
        'decimal' => 'DecimalDbMigrationColumnFormatter',
        'numeric' => 'DecimalDbMigrationColumnFormatter',
        'date' => 'DateDbMigrationColumnFormatter',
        'time' => 'TimeDbMigrationColumnFormatter',
        'datetime' => 'DateTimeDbMigrationColumnFormatter',
        'year' => 'YearDbMigrationColumnFormatter',
        'char' => 'CharDbMigrationColumnFormatter',
        'varchar' => 'VarcharDbMigrationColumnFormatter',
        'binary' => 'BinaryDbMigrationColumnFormatter',
        'varbinary' => 'BinaryDbMigrationColumnFormatter',
        'tinyblob' => 'BinaryDbMigrationColumnFormatter',
        'blob' => 'BinaryDbMigrationColumnFormatter',
        'mediumblob' => 'BinaryDbMigrationColumnFormatter',
        'longblob' => 'BinaryDbMigrationColumnFormatter',
        'tinytext' => 'TinyTextDbMigrationColumnFormatter',
        'text' => 'TextDbMigrationColumnFormatter',
        'mediumtext' => 'MediumTextDbMigrationColumnFormatter',
        'longtext' => 'LongTextDbMigrationColumnFormatter',
        'enum' => 'EnumDbMigrationColumnFormatter',
        'json' => 'JsonDbMigrationColumnFormatter',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

//        $this->getDefinition()->getOption("fromddl")->setDefault("ask");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fromDDLFilename = trim($this->argument('filename'));

        if ($tableName == "") {
            $this->info("You must specify a filename");
            return;
        }

        $migrationName = trim($this->argument('migrationname'));

        if ($migrationName == "") {
            $this->info("You must specify a database migration name");
            return;
        }

        $this->handleFromDDL($migrationName, $fromDDLFilename);
    }

    private function getCreateDDLFromFile($ddlFilename) {
	$lines = file($ddlFilename);

	$i = 0;

	$createDDLStatements = array();

	while (true) {
        	if ($i > count($lines) - 1) {
                	break;
	        }

	        $line = $lines[$i];

        	$thisLine = trim($line);

	        if ($thisLine  == "") {
        	        $i++;
                	continue;
	        }

        	if (substr($thisLine, 0, 2) == "--" || substr($thisLine, 0, 2) == "/*") {
                	$i++;
	                continue;
        	}

	        if (strtoupper(substr($thisLine, 0, 13)) != "CREATE TABLE ") {
        	        $i++;
                	continue;
	        }

        	$createTable = $thisLine;

	        while (true) {
        	        $i++;

                	$thisLine = trim($lines[$i]);

	                $createTable .= $thisLine . "\n";

        	        if (stripos($thisLine, ";") !== FALSE) {
                	        break;
	                }
        	}

		$createDDLStatements[] = $createTable;
	}

	return $createDDLStatements;
    }

    private function handleFromDDL($tableName, $migrationName, $ddlFilename)
    {
        if (!file_exists($ddlFilename)) {
            $this->error("File '$ddlFilename' does not exist");
            return;
        }

	$createDDLStatements = $this->getCreateDDLFromFile($ddlFilename);

	print_r($createDDLStatements);

	return;	

        $fileContents = trim(file_get_contents($ddlFilename));

        $parsedSchema = new Schema();

        $parser = new Parser();

        $table = $parser->parseTable($fileContents);

        $columns = $table->getColumns();

        $tableDef = "";

        if ($table->getEngine() != "") {
            $tableDef .= '              $table->engine = "' . $table->getEngine() . '";' . "\n";
        }

        if ($table->getCharacterset() != "") {
            $tableDef .= '              $table->charset = "' . $table->getCharacterset() . '";' . "\n";
        }

        if ($table->getCollation() != "") {
            $tableDef .= '              $table->collation = "' . $table->getCollation() . '";' . "\n";
        }

        $autoIncrementColumn = "";

        foreach ($columns as $colName => $column) {
            $colType = get_class($column);

            $parts = explode("\\", $colType);

            $colType = $parts[count($parts) - 1];

            $allowNullableClause = "";

            if ($column->isAllowedNull()) {
                $allowNullableClause = "->nullable()";
            }

            $uniqueClause = "";

            if ($column->isUnique()) {
                $uniqueClause = "->unique()";
            }

            $charsetClause = "";

            if ($column->getCharset() != "") {
                $charsetClause = "->charset('" . $column->getCharset() . "')";
            }

            $collateClause = "";

            if ($column->getCollate() != "") {
                $collateClause = "->collation('" . $column->getCollate() . "')";
            }

            $clauses = $allowNullableClause . $uniqueClause . $charsetClause . $collateClause;

            switch ($colType) {
                case "PrimaryKeyColumn":
                    $autoIncrementColumn = $colName;

                    $colSize = $column->getSize();

                    if ($colSize == "big") {
                        $tableDef .= '              $table->bigIncrements(\'' . $colName . '\')' . $clauses . ';' . "\n";
                    } else {
                        $tableDef .= '              $table->increments(\'' . $colName . '\')' . $clauses . ';' . "\n";
                    }

                    break;
                case "UnsignedPrimaryKeyColumn":
                    $autoIncrementColumn = $colName;

                    $colSize = $column->getSize();

                    if ($colSize == "big") {
                        $tableDef .= '              $table->bigIncrements(\'' . $colName . '\')->unsigned()' . $clauses . ';' . "\n";
                    } else {
                        $tableDef .= '              $table->increments(\'' . $colName . '\')->unsigned()' . $clauses . ';' . "\n";
                    }

                    break;
                case "BitIntegerColumn":
                    $colSize = $column->getSize();

                    $tableDef .= '              $table->boolean(\'' . $colName . '\')' . $clauses . ';' . "\n";

                    break;
                case "UnsignedBitIntegerColumn":
                    $colSize = $column->getSize();

                    $tableDef .= '              $table->boolean(\'' . $colName . '\')->unsigned()' . $clauses . ';' . "\n";

                    break;
                case "TinyIntegerColumn":
                    $colSize = $column->getSize();

                    $tableDef .= '              $table->tinyInteger(\'' . $colName . '\')' . $clauses . ';' . "\n";

                    break;
                case "UnsignedTinyIntegerColumn":
                    $colSize = $column->getSize();

                    $tableDef .= '              $table->tinyInteger(\'' . $colName . '\')->unsigned()' . $clauses . ';' . "\n";

                    break;
                case "IntegerColumn":
                    $colSize = $column->getSize();

                    if ($colSize == "") {
                        $tableDef .= '              $table->integer(\'' . $colName . '\')' . $clauses . ';' . "\n";
                    } else {
                        $tableDef .= '              $table->' . $colSize . 'Integer(\'' . $colName . '\')' . $clauses . ';' . "\n";
                    }
                    break;
                case "UnsignedIntegerColumn":
                    $colSize = $column->getSize();

                    if ($colSize == "") {
                        $tableDef .= '              $table->integer(\'' . $colName . '\')->unsigned()' . $clauses . ';' . "\n";
                    } else {
                        $tableDef .= '              $table->' . $colSize . 'Integer(\'' . $colName . '\')->unsigned()' . $clauses . ';' . "\n";
                    }
                    break;
                case "StringColumn":
                    $colLength = $column->getLength();

                    $tableDef .= '              $table->string(\'' . $colName . '\', ' . $colLength . ')' . $clauses . ';' . "\n";

                    break;
                case "DecimalColumn":
                    $width = $column->getWidth();
                    $decimalPlaces = $column->getDecimalPlaces();

                    $tableDef .= '              $table->decimal(\'' . $colName . '\',' . $width . ',' . $decimalPlaces . ')' . $clauses . ';' . "\n";
                    break;
                case "DoubleColumn":
                    $width = $column->getWidth();
                    $decimalPlaces = $column->getDecimalPlaces();

                    $tableDef .= '              $table->double(\'' . $colName . '\',' . $width . ',' . $decimalPlaces . ')' . $clauses . ';' . "\n";
                    break;
                case "FloatColumn":
                    $width = $column->getWidth();
                    $decimalPlaces = $column->getDecimalPlaces();

                    $tableDef .= '              $table->float(\'' . $colName . '\',' . $width . ',' . $decimalPlaces . ')' . $clauses . ';' . "\n";
                    break;
                case "TextColumn":
                    $colSize = $column->getSize();

                    if ($colSize == "") {
                        $tableDef .= '              $table->text(\'' . $colName . '\')' . $clauses . ';' . "\n";
                    } else {
                        $tableDef .= '              $table->' . $colSize . 'Text(\'' . $colName . '\')' . $clauses . ';' . "\n";
                    }

                    break;
                case "BlobColumn":
                    $colSize = $column->getSize();

                    $tableDef .= '              $table->binary(\'' . $colName . '\')' . $clauses . ';' . "\n";

                    break;
                case "DateColumn":
                    $tableDef .= '              $table->date(\'' . $colName . '\')' . $clauses . ';' . "\n";

                    break;
                case "DateTimeColumn":
                    $tableDef .= '              $table->datetime(\'' . $colName . '\')' . $clauses . ';' . "\n";

                    break;
                case "TimestampColumn":
                    $tableDef .= '              $table->timestamp(\'' . $colName . '\')' . $clauses . ';' . "\n";

                    break;
            }
        }

        $indexes = $table->getIndexes();

        foreach ($indexes as $indexName => $index) {
            $indexName = $index->getName();

            $indexColumns = $index->getColumns();

            if (count($indexColumns) == 1) {
                $indexColumn = '\'' . $indexColumns[0] . '\'';
            } else {
                $indexColumn = 'array(';
                $firstTime = true;

                foreach ($indexColumns as $colName) {
                    if (!$firstTime) {
                        $indexColumn .= ", ";
                    } else {
                        $firstTime = false;
                    }

                    $indexColumn .= '\'' . $colName . '\'';
                }

                $indexColumn .= ")";
            }

            if ($index->getType() == "unique") {
                $tableDef .= '              $table->unique(' . $indexColumn . ', \'' . $indexName . '\');' . "\n";
            } else if ($index->getType() == "primary") {
                if ($autoIncrementColumn == "") {
                    $tableDef .= '              $table->primary(' . $indexColumn . ');' . "\n";
                }
            } else {
                $tableDef .= '              $table->index(' . $indexColumn . ', \'' . $indexName . '\');' . "\n";
            }
        }

        $foreignKeys = $table->getForeignKeys();

        foreach ($foreignKeys as $fkName => $fk) {
            $colName = $fk->getColumnName();
            $parentTableName = $fk->getParentTableName();
            $parentTableColName = $fk->getParentTableColumnName();
            $onDelete = $fk->getOnDelete();
            $onUpdate = $fk->getOnUpdate();
            $onDeleteClause = "";
            $onUpdateClause = "";

            if ($onDelete != "") {
                $onDeleteClause = '->onDelete(\'' . $onDelete . '\')';
            }

            if ($onUpdate != "") {
                $onUpdateClause = '->onUpdate(\'' . $onUpdate . '\')';
            }

            $tableDef .= '              $table->foreign(\'' . $colName . '\',\'' . $fkName . '\')->references(\'' . $parentTableColName . '\')->on(\'' . $parentTableName . '\')' . $onDeleteClause . $onUpdateClause . ';' . "\n";
        }

        $createStubFile = __DIR__ . "/stubs/create.stub";

        $createStub = file_get_contents($createStubFile);

        $outputText = str_replace("DummyTable", $tableName, $createStub);

        $outputText = str_replace("[[TABLEDEF]]", $tableDef, $outputText);

        $outputText = str_replace("DummyClass", $migrationName, $outputText);

        $filename = base_path() . "/database/migrations/" . date("Y_m_d_His") . "_$migrationName" . ".php";

        file_put_contents($filename, $outputText);

        $this->info("Migration script written to $filename");
    }

    protected function parseDDL($ddl)
    {
    }

    protected function getOptions()
    {
        echo "Get options\n";

        return [
            ['fromddl', 'ask', InputOption::VALUE_REQUIRED, 'The file that contains the create DDL.'],
        ];
    }
}
