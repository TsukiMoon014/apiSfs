<?php

namespace apiSfs\core\Database;

interface ConnectionInterface
{
    public static function getConnection();

    public function prepare($sql);

    public function query($sql);
}