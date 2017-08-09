<?php

namespace apiSfs\src\Maxmind;

interface MaxmindInterface
{
    public function getIpInfos($ip);
}