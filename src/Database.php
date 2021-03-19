<?php
namespace LinksApplication;

/**
 * Trait Database
 * common mysql functions
 */
trait Database
{
    private ?\mysqli $connection = NULL;

    /**
     * Get mysqli instance
     * @return mysqli|\mysqli|null
     */
    protected function getConnection():\mysqli
    {
        return $this->connection = ($this->connection instanceof \mysqli) ? $this->connection :
            new \mysqli(
                settings['database']['hostname'],
                settings['database']['username'],
                settings['database']['password'],
                settings['database']['dbname']
            );
    }
}
