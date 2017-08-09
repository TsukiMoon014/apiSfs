<?php

namespace apiSfs\core\Database;

abstract class AbstractConnection
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

}