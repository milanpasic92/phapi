<?php

namespace Phapi\Application;

use Phalcon\DI;

class ApiResponse
{

    /** @var string|int|array $data */
    public $data;
    public array $meta;

    public function __construct($data, array $meta = [])
    {
        $this->data = $data;
        $this->meta = $meta;

        $di = DI::getDefault();
        if ($di->has('user')) {
            $this->meta['access_token'] = $di->get('user')->token;
        }
    }
}
