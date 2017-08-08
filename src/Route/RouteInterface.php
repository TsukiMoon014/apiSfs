<?php

namespace apiSfs\src\route;

interface RouteInterface
{
    public function loadRoutes();

    public function runRouter();
}