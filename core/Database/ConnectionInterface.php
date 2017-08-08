<?php

namespace apiSfs\core\database;

interface ConnectionInterface
{
    public static function getConnection();

    public function prepare($sql);

    public function query($sql);
}