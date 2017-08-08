<?php

namespace apiSfs\core\database;

abstract class AbstractConnection
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

}