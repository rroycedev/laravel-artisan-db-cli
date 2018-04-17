<?php

namespace Roycedev\DbCli\Schema\Renderer;

use Roycedev\DbCli\SchemaInterface;

/**
 * Take the Schema and return a digraph to render in graphviz
 */
class GraphViz
{
    /**
     *
     * @param SchemaInterface $schema
     * @return string graphviz syntax
     */
    public function render(SchemaInterface $schema)
    {
        return '';
    }
}
