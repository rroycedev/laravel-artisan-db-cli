<?php

namespace Roycedev\DbCli\Schema\Table\Index;

/**
 * Class Column
 */
class ForeignKey
{
    private $name;
    private $columnName;
    private $parentTable;
    private $parentTableColumn;
    private $onDelete;
    private $onUpdate;

    public function __construct($name, $columnName = "", $parentTable = "", $parentTableColumn = "", $onDelete = "", $onUpdate = "")
    {
        $this->name = $name;
        $this->columnName = $columnName;
        $this->parentTable = $parentTable;
        $this->parentTableColumn = $parentTableColumn;
        $this->onDelete = $onDelete;
        $this->onUpdate = $onUpdate;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getColumnName()
    {
        return $this->columnName;
    }

    public function getParentTableName()
    {
        return $this->parentTable;
    }

    public function getParentTableColumnName()
    {
        return $this->parentTableColumn;
    }

    public function getOnDelete()
    {
        return $this->onDelete;
    }

    public function getOnUpdate()
    {
        return $this->onUpdate;
    }
}
