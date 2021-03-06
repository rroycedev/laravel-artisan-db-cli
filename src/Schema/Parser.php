<?php

namespace Roycedev\DbCli\Schema;

use Roycedev\DbCli\Schema;
use Roycedev\DbCli\SchemaInterface;
use Roycedev\DbCli\Schema\Table\ColumnFactory;
use Roycedev\DbCli\Schema\Table\ColumnFactoryInterface;
use Roycedev\DbCli\Schema\Table\Index;
use Roycedev\DbCli\Schema\Table\Index\ForeignKey;
use Roycedev\DbCli\Schema\Table\TableFactory;
use Roycedev\DbCli\Schema\Table\TableFactoryInterface;

/**
 * Class Parser
 * @package Roycedev\DbCli\Schema
 */
class Parser
{
    /** @var array */
    private $ignoredTables = [];

    /** @var ColumnFactoryInterface */
    private $columnFactory;

    /** @var TableFactoryInterface */
    private $tableFactory;

    /**
     * @param array                $ignoreTables
     * @param ColumnFactoryInterface | null $columnFactory
     */
    public function __construct($ignoreTables = [], ColumnFactoryInterface $columnFactory = null, TableFactoryInterface $tableFactory = null)
    {
        $this->ignoredTables = $ignoreTables;
        if (empty($columnFactory)) {
            $columnFactory = new ColumnFactory();
        }
        $this->columnFactory = $columnFactory;
        if (empty($tableFactory)) {
            $tableFactory = new TableFactory();
        }
        $this->tableFactory = $tableFactory;
    }

    /**
     * Schema is a parameter so you can parse several snippets of sql incrementally
     * @param SchemaInterface $schema where to add the parsed tables.
     * @param string $sql    string containing sql to be parsed: should be in format as produced by phpMyAdmin export
     *                       oops! what if an sql comment has a ; in it?
     *                       match end of line perhaps?
     */
    public function parse(SchemaInterface $schema, $sql)
    {
        $tables = preg_split('/;[\r\n]/ms', $sql, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($tables as $tableSQL) {
            $tableSQL = trim($tableSQL);
            if (strlen($tableSQL) <= 0) {
                continue;
            }
            $table = $this->parseTable($tableSQL);
            if (!empty($table)) {
                $schema->add($table);
            }
        }
    }

    /**
     * assumes each column is on its own line, with the CREATE TABLE and
     * table type info also on their own lines, as returned by MySQL
     * @param string $tableSql
     * @return TableInterface |null
     * @throws \Exception
     */
    public function parseTable($tableSql)
    {
        $patterns = require 'Patterns.php';

        $tablePatterns = $patterns["table"];

        // break the sql into first line, columns, last line.

        if (!preg_match($tablePatterns['createtable'], $tableSql, $matches)) {

            if (!preg_match($patterns['createtableifnotexists'], $tableSql, $matches)) {
                throw new \Exception('Invalid sql ' . $tableSql);
            }
        }

        $engine = "";

        $engineMatches = preg_match($tablePatterns['engine'], $matches[count($matches) - 1], $fields);

        if ($engineMatches) {
            $engine = $fields[1];
        }

        // Character Set option

        $charset = "";

        $charsetMatches = preg_match($tablePatterns['charset'], $matches[count($matches) - 1], $fields);

        if ($charsetMatches) {
            $charset = str_replace(";", "", trim(str_replace("=", "", trim($fields[1]))));
        }

        // Collate option

        $collate = "";

        $collateMatches = preg_match($tablePatterns['collate'], $matches[count($matches) - 1], $fields);

        if ($collateMatches) {
            $collate = str_replace(";", "", trim(str_replace("=", "", trim($fields[1]))));
        }

        $name = Schema::unQuote($matches[1]);
        if (in_array($name, $this->ignoredTables)) {
            return null;
        }

        $table = $this->tableFactory->createTable($name, $this->extractComment($matches[3]), $engine, $charset, $collate);

        $this->parseColumns($table, $matches[2]);
        return $table;
    }

    /**
     *
     * known limitation: one column per line: a \n is required between each column definition
     * known limitation: single field primary key
     * @param TableInterface  $table
     * @param string $columnText
     * @return TableInterface
     */
    public function parseColumns(TableInterface $table, $columnText)
    {
        // remove stuff we don't want to parse or don't currently care about
        // $columnText = preg_replace('/\s+default\s*\'\'/ims', '', $columnText);
        // $columnText = preg_replace('/\s+default\s*\'[-0:. ]+\'/ims', '', $columnText);
        // later: add charset stuff in here

        $patterns = require __DIR__ . '/Patterns.php';

        $columns = preg_split($patterns['table']['objecttype'], $columnText, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($columns as $column) {
            $column = $this->removeTrailingComma($column);
            $comment = $this->extractComment($column);
            $column = preg_replace($patterns['table']['comment'], '', $column); // snip off the comment

            if (preg_match($patterns['table']['primarykey'], $column, $fields)) {
                assert(strpos(',', $fields[1]) === false); // known limitation: single field primary key
                $table->addIndex(new Index\Primary(Schema::unQuote(trim($fields[1]))));
            } else if (preg_match($patterns['table']['uniquekey'], $column, $fields)) {
                array_shift($fields); // delete $fields[0] coz it is the whole string
                $fields = array_map('Roycedev\DbCli\Schema::unQuote', $fields);
                $name = array_shift($fields);
                $table->addIndex(new Index\Unique($name, $fields[0]));
            } elseif (preg_match($patterns['table']['fulltext'], $column, $fields)) {
                array_shift($fields); // delete $fields[0] coz it is the whole string
                $fields = array_map('Roycedev\DbCli\Schema::unQuote', $fields);
                $name = array_shift($fields);
                $table->addIndex(new Index($name, $fields[0], 'fulltext'));
            } elseif (preg_match($patterns['table']['foreignkey'], $column, $fields)) {
                array_shift($fields); // delete $fields[0] coz it is the whole string

                $fields = array_map('Roycedev\DbCli\Schema::unQuote', $fields);

                $fkName = array_shift($fields);
                $fkColName = array_shift($fields);
                $parentTableName = array_shift($fields);
                $parentTableColName = array_shift($fields);

                $onDelete = "";
                $onUpdate = "";

                if (count($fields) > 0) {
                    $clause = $fields[0];

                    $matched = preg_match('/ON DELETE\s\\b(\\w+)+\\b/', $clause, $clauseFields);

                    if ($matched) {
                        $onDelete = strtolower($clauseFields[1]);
                    }

                    $matched = preg_match('/ON UPDATE\s\\b(\\w+)+\\b/', $clause, $clauseFields);

                    if ($matched) {
                        $onUpdate = strtolower($clauseFields[1]);
                    }
                }
                $table->addForeignKey(new ForeignKey($fkName, $fkColName, $parentTableName, $parentTableColName, $onDelete, $onUpdate));
            } elseif (preg_match($patterns['table']['key'], $column, $fields)) {
                array_shift($fields); // delete $fields[0] coz it is the whole string
                $fields = array_map('Roycedev\DbCli\Schema::unQuote', $fields);
                $name = array_shift($fields);
                $table->addIndex(new Index($name, $fields[0]));
            } else {
                // ordinary field: name is first word (optionally quoted), data type is rest of string
                preg_match($patterns['table']['column'], $column, $matches);

                $name = Schema::unQuote($matches[1]);

                try {
                    $table->addColumn($this->columnFactory->create($name, $comment, $matches[2]));
                } catch (\Exception $ex) {
                    throw new \Exception("Error processing table " . $table->getName() . ": " . $ex->getMessage());
                }
            }
        }

        return $table;
    }

    /**
     * snip off optional trailing comma and white space
     * @param $sql string to trim
     * @return  string
     */
    public function removeTrailingComma($sql)
    {
        return trim($sql, ' ,');
    }

    /**
     * assumes the comment is single quote enclosed at the end of the
     * string. Replaces SQL escaping of single quotes, which replaces a single
     * quote with two eg ' becomes ''
     * Note that comments on the table are "comment='my comment'" and comments
     * on the column are "comment 'my comment'" (no equals sign). Doh.
     * @param string $sql string containing de-escaped comment
     * @return string comment text
     */
    public function extractComment($sql)
    {
        if (preg_match('/comment\s*(?:=)?\s*\'(.*)\'/im', $sql, $comment)) {
            return Schema::unQuote($comment[1]);
        }

        return '';
    }
}
