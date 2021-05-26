<?php

namespace Phapi\Application;

interface ResponseInterface{

    public function __construct($errors, array $meta = []);

}