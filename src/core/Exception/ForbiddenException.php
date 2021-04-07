<?php

namespace core\Exception;

use core\Application;

class ForbiddenException extends \Exception
{
    protected $message = "You don't have permission to see this page";
    protected $code = 403;
}