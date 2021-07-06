<?php

namespace Phapi\Application;

use Phalcon\Config;

class ConfigProvider
{
    protected Config $config;

    public function get(){
        return $this->config;
    }

    public function __construct()
    {
        $this->config = new Config(
            [
                'profilerEnabled' => getenv('PROFILER_ENABLED'),

                'jwtIssuer'       => getenv('JWT_ISSUER'),
                'jwtKey'          => getenv('JWT_KEY'),

                'database' => [
                    'adapter'     => 'Mysql',
                    'host'        => getenv('DB_HOST') . ':' . getenv('DB_PORT'),
                    'hostname'    => getenv('DB_HOST'),
                    'username'    => getenv('DB_USERNAME'),
                    'password'    => getenv('DB_PASSWORD'),
                    'dbname'      => getenv('DB_NAME'),
                    'charset'     => getenv('DB_CHARSET'),
                ],

                /* Not Used */
                'monolog' => [
                    'loggly'        => getenv('TOKEN_LOGGLY'),
                    'slack'         => getenv('TOKEN_SLACK'),
                    'slackChannel'  => getenv('SLACK_CHANNEL'),
                    'slackUsername' => getenv('SLACK_USERNAME'),
                ],

                /* Not Used */
                'aws' => [
                    'access_id'       => getenv('AWS_ACCESS_KEY_ID'),
                    'secret_key'      => getenv('AWS_SECRET_ACCESS_KEY'),
                    'bucket'          => getenv('AWS_S3_BUCKET_NAME'),
                    'bucketPublicUrl' => getenv('AWS_S3_BUCKET_URL'),
                ],

                'namespaces' => [
                    'Phapi\Application'   => '/app/application/',
                    'Phapi\Controllers'   => '/app/controllers/',
                    'Phapi\Exceptions'    => '/app/exceptions/',
                    'Phapi\Models'        => '/app/models/',
                    'Phapi\Services'      => '/app/services/',
                    'Phapi\Routes'        => '/app/routes/',
                    'Phapi\Utility'       => '/app/utility/',
                    'Phapi\Repository'    => '/app/repository/',
                ]
            ]
        );
    }
}
