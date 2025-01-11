<?php

namespace App\Exceptions;

use Exception;

class ArticleNotTranslatedException extends Exception
{
    protected $message = 'Article not translated';
    protected $code = 404;
    public function __construct(protected string $details)
    {
        parent::__construct();
    }
    public function getDetails(): string {
        return $this->details;
    }
}
