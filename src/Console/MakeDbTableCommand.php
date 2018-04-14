<?php

namespace Roycedev\DbCli\Console;

use Illuminate\Console\Command;
use Roycedev\DbCli\Schema;
use Roycedev\DbCli\Schema\Parser;

class MakeDbTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbcli:maketable {tablename? : The name of the table to create.} {migrationname? : The name of the database migration}
            {--fromddl=ask : Create database migration from DDL.  If specifiy with no filename, user will be prompted for filename.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a database migration script and model for a database table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->getDefinition()->getOption("fromddl")->setDefault("ask");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("dbcli:maketable: Starting....\n");

        $tableName = $this->argument('tablename');

        if ($tableName == "") {
            $this->info("You must specify a table name");
            return;
        }

        $migrationName = trim($this->argument('migrationname'));

        if ($migrationName == "") {
            $this->info("You must specify a database migration name");
            return;
        }

        $fromDDLFilename = $this->option('fromddl');

        if ($fromDDLFilename == "") {
            $this->handleInteractive($tableName, $migrationName);
        } else {
            $this->handleFromDDL($tableName, $migrationName, $fromDDLFilename);
        }
    }

    private function handleInteractive($tableName, $migrationName)
    {
    }

    private function handleFromDDL($tableName, $migrationName, $ddlFilename)
    {
        if (!file_exists($ddlFilename)) {
            $this->error("File '$ddlFilename' does not exist");
            return;
        }

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

}
