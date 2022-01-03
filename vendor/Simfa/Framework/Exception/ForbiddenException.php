<?php

namespace Simfa\Framework\Exception;

class ForbiddenException extends \Exception
{
    protected $message = "You don't have permission to see this page";
    protected $code = 403;
}
