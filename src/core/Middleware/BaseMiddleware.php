<?php


namespace core\Middleware;


abstract class BaseMiddleware
{
    abstract public function execute();
}