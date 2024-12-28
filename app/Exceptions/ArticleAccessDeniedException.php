<?php

namespace App\Exceptions;

use Exception;

class ArticleAccessDeniedException extends Exception
{
    protected $message = 'Not enough rights';
    protected $code = 403;
}
