<?php

namespace Simfa\Framework\Exception;

class NotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = 'The Page You Are Trying To Access Is Not Found';
}
