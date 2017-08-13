<?php

namespace apiSfs\core\Database;

/**
 * Interface ConnectionInterface
 *
 * Provides database connection logic
 *
 * @package apiSfs\core\Database
 */
interface ConnectionInterface
{
    /*
     * Gets database connection
     */
    public static function getConnection();

    /*
     * Defines a method used for prepared statements
     */
    public function prepare($sql);

    /*
     * Sends an SQL request to database
     */
    public function query($sql);
}