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
                    'host'        => getenv('DB_HOST') . ':3306',
                    'hostname'    => getenv('DB_HOST'),
                    'username'    => getenv('DB_USERNAME'),
                    'password'    => getenv('DB_PASSWORD'),
                    'dbname'      => getenv('DB_NAME'),
                    'charset'     => getenv('DB_CHARSET'),
                ],

                'rtt_database' => [ // used for response time tracking
                    'adapter'     => 'Mysql',
                    'host'        => getenv('RTT_DB_HOST') . ':3306',
                    'hostname'    => getenv('RTT_DB_HOST'),
                    'username'    => getenv('RTT_DB_USERNAME'),
                    'password'    => getenv('RTT_DB_PASSWORD'),
                    'dbname'      => getenv('RTT_DB_NAME'),
                    'charset'     => getenv('RTT_DB_CHARSET'),
                ],

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
                    'Phapi\Application'   => '/app/Application/',
                    'Phapi\Controllers'   => '/app/Controllers/',
                    'Phapi\Dto'           => '/app/Dto/',
                    'Phapi\Exceptions'    => '/app/Exceptions/',
                    'Phapi\Models'        => '/app/Models/',
                    'Phapi\Repository'    => '/app/Repository/',
                    'Phapi\Routes'        => '/app/Routes/',
                    'Phapi\Services'      => '/app/Services/',
                    'Phapi\Utility'       => '/app/Utility/',
                    'Phapi\Validators'    => '/app/Validators/',
                ]
            ]
        );
    }
}
