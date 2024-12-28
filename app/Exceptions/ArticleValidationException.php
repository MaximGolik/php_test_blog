<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class ArticleValidationException extends Exception
{
    //todo разобраться, можно ли пихать $validatedData в exception
    protected $errors;
    protected $validatedData;

    public function __construct(MessageBag $errors, $validatedData)
    {
        $this->errors = $errors;
        $this->validatedData = $validatedData;
        parent::__construct('Validation failed');
    }
    public function getErrors(): MessageBag
    {
        return $this->errors;
    }
    public function getValidatedData()
    {
        return $this->validatedData;
    }
}
