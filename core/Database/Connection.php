<?php

namespace apiSfs\core\Database;

/**
 * Class Connection
 * Provides database connection
 * @package apiSfs\core\Database
 */
class Connection implements ConnectionInterface
{
    private $pdo = null;
    private static $_connection = null;

    /**
     * Connection constructor.
     */
    private function __construct()
    {
        try {
            $this->pdo = new \PDO("mysql:host=".DATABASE['host'].";dbname=".DATABASE['database_name'].";charset=utf8", DATABASE['login'], DATABASE['password']);
        } catch (\PDOException $exception) {
            echo 'Failed to connect to database: '.$exception->getMessage();
        }
        self::$_connection = true;
    }

    /**
     * Return connnection instance
     * @return bool|Connection|null
     */
    public static function getConnection()
    {
        if (null === self::$_connection) {
            self::$_connection = new self;
        }
        return self::$_connection;
    }

    /**
     * Implements PDO prepare() method
     * @param $query
     * @return \PDOStatement
     */
    public function prepare($query)
    {
        return $this
            ->pdo
            ->prepare($query)
        ;
    }

    /**
     * Implements PDO query() method
     * @param $sql
     * @return \PDOStatement
     */
    public function query($sql)
    {
        return $this
            ->pdo
            ->query($sql)
        ;
    }


}