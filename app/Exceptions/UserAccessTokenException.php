<?php

namespace App\Exceptions;

use Exception;

class UserAccessTokenException extends Exception
{
    protected $message = 'User not found by access token';
    protected $code = 400;
}
