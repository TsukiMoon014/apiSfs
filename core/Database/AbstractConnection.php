<?php

namespace apiSfs\core\Database;

/**
 * Class AbstractConnection
 *
 * Provides a constructor for models
 * 
 * @package apiSfs\core\Database
 */
abstract class AbstractConnection
{
    protected $connection;


    /**
     * AbstractConnection constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

}