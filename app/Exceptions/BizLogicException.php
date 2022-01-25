<?php

namespace Phapi\Exceptions;

use Phalcon\DI;
use Phapi\Application\ApiError;

class BizLogicException extends BaseException
{
    const KEY_PREFIX = "beError.";

    protected string $err_key;
    protected array $details;
    protected $code;

    public static array $messages = [
        /*
           key value pairs like:
        "shortcutNotValid" => "User lacks permission to see object for which shortcut is for.",
         */
    ];

    public function __construct($code, string $err_key, array $details = [])
    {
        $this->code = $code;
        $this->err_key = $err_key;
        $this->details = $details;

        parent::__construct($this->messages[$err_key], $this->code);
    }


    public function handle()
    {
        $di = DI::getDefault();

        $data = [
            'errors' => [
                [
                    'err_key' => self::KEY_PREFIX.$this->err_key,
                    'message' => $this->messages[$this->err_key],
                    'details' => $this->details
                ]
            ],
            'meta' => [
                'status_code' => $this->code
            ]
        ];

        $response = new ApiError($data['errors'], $data['meta']);
        $di->get('rest')->sendResponse($response);
    }

}