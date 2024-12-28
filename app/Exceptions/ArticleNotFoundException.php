<?php

namespace App\Exceptions;

use Exception;

class ArticleNotFoundException extends Exception
{
    protected $message = 'Article not found';
    protected $code = 404;
}
