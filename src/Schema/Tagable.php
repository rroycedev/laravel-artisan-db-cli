<?php

namespace Roycedev\DbCli\Schema;

/**
 * ability to tag a column or table
 */
trait Tagable
{
    /** @var array string */
    private $tags = [];

    /**
     * @param string $tag
     * @return $this
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * @param string $tag
     * @return boolean
     */
    public function hasTag($tag)
    {
        return in_array($tag, $this->tags);
    }
}
