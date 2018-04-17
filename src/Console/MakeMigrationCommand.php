<?php

namespace Roycedev\DbCli\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Roycedev\DbCli\Schema;
use Roycedev\DbCli\Schema\Parser;

class MakeMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbcli:makemigration {--type? : Type of migration.  Values are \'createtable\' or \'altertable\'} {--filename? : The filename containing the DDL.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates database migration scripts interactively or from a DDL script that contains multiple CREATE TABLE DDL';

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
	$type = trim(strtolower($this->option('type'));

	if ($type == "") {
		$this->info('You must specify the option --type');
		return;
	}

	if ($type != 'createtable' && $type != 'altertable') {
		$this->info('Invalid value \'' . $type . '\' for option --type');
		return;
	}

        $fromDDLFilename = trim($this->argument('filename'));

        if ($fromDDLFilename == "") {
            $this->info("You must specify a filename");
            return;
        }

        if ($fromDDLFilename == "") {
            $this->handleInteractive($type);
        } else {
            $this->handleFromDDL($type, $fromDDLFilename);
        }
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

    private function handleFromDDL($type, $ddlFilename)
    {
        if (!file_exists($ddlFilename)) {
            $this->error("File '$ddlFilename' does not exist");
            return;
        }

	$createDDLStatements = $this->getCreateDDLFromFile($ddlFilename);

	$tables = array();

	foreach ($createDDLStatements as $createDDL) {
		$tables[] = $this->getTableFromDDL($createDDL);
	}

	$orderedTables = array();

	//  Add tables without foreign keys first

	foreach ($tables as $table) {
		if (count($table->getForeignKeys()) == 0) {
			$orderedTables[] = $table;
		}
	}

	foreach ($tables as $table) {
                if (count($table->getForeignKeys()) == 0) {
			continue;
                }

		$orderedTables[] = $table;
	}

	echo "Processing tables in this order:\n\n";

	foreach ($orderedTables as $table) {
		echo $table->getName() . "\n";
	}

	echo "\n";

	$tableNum = 0;

	foreach ($orderedTables as $table) {
		$tableNum++;

		$this->processTable($table, $tableNum);
	}
    }

    private function getTableFromDDL($createDDL)
    {
        $parsedSchema = new Schema();

        $parser = new Parser();

        $table = $parser->parseTable($createDDL);

	return $table;
    }

    private function formatMigrationName($tableName) 
    {
        $parts = explode("_", $table->getName());

        $migrationName = "";

        foreach ($parts as $part) {
                $migrationName .= ucfirst($part);
        }

	return $migrationName;
    }

    private function processTable($table, $tableNum) 
    {
	$migrationName = $this->formatMigrationName($tableName);

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
	else {
		if ($table->getCharacterset() != "") {
		        $row = DB::select('show character set like \'' . $table->getCharacterset() . '\'');

			if (!$row || count($row) == 0) {
				throw new \Exception("Default collation for character set '" . $table->getCharacterset() . "' does not exist");
			}

			$def = $row[0];

			$colName = "Default collation";

	                $tableDef .= '              $table->collation = "' . $def->$colName . '";' . "\n";
		}
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
                case "CharColumn":
                    $colLength = $column->getLength();

                    $tableDef .= '              $table->char(\'' . $colName . '\', ' . $colLength . ')' . $clauses . ';' . "\n";

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
                    $tableDef .= '              $table->primary(' . $indexColumn . ', \'pk_index\');' . "\n";
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

        $outputText = str_replace("DummyTable", $table->getName(), $createStub);

        $outputText = str_replace("[[TABLEDEF]]", $tableDef, $outputText);

        $outputText = str_replace("DummyClass", $migrationName, $outputText);

        $filename = base_path() . "/database/migrations/" . date("Y_m_d") . "_" . str_pad($tableNum, 5, "0", STR_PAD_LEFT) . "_$migrationName" . ".php";

        file_put_contents($filename, $outputText);

        $this->info("Migration script written to $filename");
    }

    protected function parseDDL($ddl)
    {
    }

    protected function getOptions()
    {
        return [
            ['fromddl', 'ask', InputOption::VALUE_REQUIRED, 'The file that contains the create DDL.'],
        ];
    }


    private function handleInteractive($type)
    {
        $this->info("In order to generate a database migration script for a table, you will be asked to enter information\n");

	while (true) {
		$tableName = trim($this->ask('Table name'));

		if ($tableName != "") {
			break;
		}
	}

        $columns = $this->getColumnsFromInput();

        $indexes = array();

        if ($this->confirm('Are there any indexes to define', true)) {
            $indexes = $this->getIndexesFromInput($columns);
        }

        $foreignKeys = array();

        if ($this->confirm('Are there any foreign keys to define', true)) {
            $foreignKeys = $this->getForeignKeysFromInput($columns);
        }

        $tableComment = $this->ask('Table comment');

        $this->generateMigration($tableName, $columns, $indexes, $foreignKeys, $tableComment);
    }

    private function generateMigration($tableName, $columns, $indexes, $foreignKeys, $tableComment)
    {
        $tableDef = "";

        foreach ($columns as $column) {
            $formatterClassName = "Roycedev\\DbCli\\Console\\Formatters\\" . $this->columnDataTypeMapping[$column->dataType];

            echo "Class name: $formatterClassName \n";

            $formatter = new $formatterClassName($column);

            $tableDef .= $formatter->toText() . "\n";
        }

        foreach ($indexes as $index) {
                $tableDef .= $index->toText() . "\n";
        }

        foreach ($foreignKeys as $fk) {
                $tableDef .= $fk->toText() . "\n";
        }

        $createStubFile = __DIR__ . "/stubs/create.stub";

        $createStub = file_get_contents($createStubFile);

        $outputText = str_replace("DummyTable", $tableName, $createStub);

        $outputText = str_replace("[[TABLEDEF]]", $tableDef, $outputText);

        $outputText = str_replace("DummyClass", $migrationName, $outputText);

        $filename = base_path() . "/database/migrations/" . date("Y_m_d_His") . "_$migrationName" . ".php";

        file_put_contents($filename, $outputText);

        print_r($outputText);

        $this->info("Migration script written to $filename");

    }

    private function getColumnsFromInput()
    {
        $columns = array();

        while (true) {
            $column = $this->getColumnFromInput(count($columns) + 1);
            if (!$column) {
                break;
            }

            $columns[] = $column;

            if (!$this->confirm("More columns", true)) {
                break;
            }
        }

        return $columns;
    }

    public function getIndexesFromInput($columns)
    {
        $indexes = array();

        while (true) {
            $index = $this->getIndexFromInput(count($indexes) + 1, $columns);
            if (!$index) {
                break;
            }

            $indexes[] = $index;

            if (!$this->confirm("More indexes", true)) {
                break;
            }
        }

        return $indexes;
    }

    public function getForeignKeysFromInput($columns)
    {
        $fks = array();

        while (true) {
            $fk = $this->getForeignKeyFromInput(count($fks) + 1, $columns);
            if (!$fk) {
                break;
            }

            $fks[] = $fk;

            if (!$this->confirm("More foreign keys", true)) {
                break;
            }
        }

        return $fks;
    }

    private function getForeignKeyFromInput($fkNum, $columns)
    {
        $this->info("Please enter the information for foreign key $fkNum:\n");

        while (true) {
            $fkName = $this->ask('Foreign Key Name');

            if ($fkName != "") {
                break;
            }
        }

        $fkColumns = array();

        $fkColNames = array();

        foreach ($columns as $column) {
            $fkColNames[] = $column->colName;
        }

        while (true) {
            $fkColName = $this->choice("Foreign Key Column " . (count($fkColumns) + 1), $fkColNames);

            $fkColumns[] = $fkColName;

            if (!$this->confirm("More foreign key columns", false)) {
                break;
            }
        }

        while (true) {
            $parentTableName = $this->ask('References table name');

            if ($parentTableName != "") {
                break;
            }
        }

        $refColumns = array();

        while (true) {
            $refColName = $this->ask("References table column " . (count($refColumns) + 1));

            $refColumns[] = $refColName;

            if (!$this->confirm("More references table columns", false)) {
                break;
            }
        }

        return new ForeignKey($fkName, $fkColumns, $parentTableName, $refColumns);
    }

    private function getIndexFromInput($indexNum, $columns)
    {
        $this->info("Please enter the information for index $indexNum:\n");

        while (true) {
            $indexName = $this->ask('Index Name');

            if ($indexName != "") {
                break;
            }
        }

        $indexColumns = array();

        $indexColNames = array();

        foreach ($columns as $column) {
            $indexColNames[] = $column->colName;
        }

        while (true) {
            $indexColName = $this->choice("Index Column " . (count($indexColumns) + 1), $indexColNames);

            $indexColumns[] = $indexColName;

            if (!$this->confirm("More index columns", true)) {
                break;
            }
        }

        $indexType = $this->choice("Index Type", ['Primary', 'Unique', 'Non-Unique']);;

        return new Index($indexName, $indexColumns, $indexType);
    }

    private function getColumnFromInput($colNum)
    {
        $length = 0;
        $decimalPlaces = 0;
        $unsigned = false;
        $charset = "";
        $collation = "";
        $autoIncrement = false;

        $this->info("Please enter the information for column $colNum:\n");

        while (true) {
            $colName = $this->ask('Column Name');

            if ($colName != "") {
                break;
            }
        }

        $dataTypes = array();

        foreach ($this->columnDataTypeMapping as $dataType => $formatterClassName) {
            $dataTypes[] = $dataType;
        }

        $dataType = $this->choice('Data Type', $dataTypes, null);

        if (CommandHelper::dataTypeSupportsLength($dataType)) {
            $length = $this->ask('Length');
        }

        if (CommandHelper::dataTypeSupportsDecimalPlaces($dataType)) {
            $decimalPlaces = $this->ask('Decimal places');
        }

        if (CommandHelper::dataTypeSupportsUnsigned($dataType)) {
            $unsigned = $this->confirm('Unsigned', false);
        }

        $allowNulls = $this->confirm('Allow NULL values', true);

        if (CommandHelper::dataTypeSupportsCharset($dataType)) {
            $charset = $this->ask('Character set [leave empty for default character set]');
            $collation = $this->ask('Collation [leave empty for default character set]');
        }

        if (CommandHelper::dataTypeSupportsAutoIncrement($dataType)) {
            $autoIncrement = $this->confirm('Auto Increment', false);

            if ($autoIncrement) { //  Set allow nulls to false if auto increment
                $allowNulls = false;
            }
        }

        $defaultValue = $this->ask('Default value');

        $comment = $this->ask('Comment');

        return new Column($colName, $dataType, $length, $decimalPlaces, $unsigned, $allowNulls, $charset, $collation, $autoIncrement, $defaultValue, $comment);
    }

}
