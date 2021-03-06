<?php

namespace Phapi\Application;

class ApiError extends Response
{
    public array $errors;
    public array $meta;

    public function __construct($errors, array $meta = [])
    {
        $this->errors = $errors;
        $this->meta = $meta;
    }

}
