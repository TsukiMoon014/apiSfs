<?php

namespace apiSfs\src\Route;

interface RouteInterface
{
    public function loadRoutes();

    public function runRouter();
}