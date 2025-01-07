<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class ArticleValidationException extends Exception
{
    protected $errors;

    public function __construct(MessageBag $errors, $validatedData)
    {
        $this->errors = $errors;
        parent::__construct('Validation failed');
    }
    public function getErrors(): MessageBag
    {
        return $this->errors;
    }
}
