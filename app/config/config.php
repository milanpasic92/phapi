<?php

if(!defined('ROOT')) { define('ROOT', dirname(__DIR__));}
if(!defined('APPLICATION_ENV')) { define('APPLICATION_ENV', getenv('APPLICATION_ENV'));}
if(!defined('PROJECT_NAME')) { define('PROJECT_NAME', getenv('COMPOSE_PROJECT_NAME'));}

return new Phalcon\Config(
    [
        'database' => [
            'adapter' => 'Mysql',
            'host'     => getenv('DB_HOST') . ':' . getenv('DB_PORT'),
            'hostname' => getenv('DB_HOST'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'dbname'   => getenv('DB_NAME'),
            'charset'  => getenv('DB_CHARSET'),
        ],

        /* Not Used */
        'monolog'   => [
            'loggly'          => getenv('TOKEN_LOGGLY'),
            'slack'           => getenv('TOKEN_SLACK'),
            'slackChannel'    => getenv('SLACK_CHANNEL'),
            'slackUsername'   => getenv('SLACK_USERNAME'),
        ],

        /* Not Used */
        'aws' => [
            'access_id' => getenv('AWS_ACCESS_KEY_ID'),
            'secret_key' => getenv('AWS_SECRET_ACCESS_KEY'),
            'bucket' => getenv('AWS_S3_BUCKET_NAME'),
            'bucketPublicUrl' => getenv('AWS_S3_BUCKET_URL'),
        ],

        'namespaces' => [
            'Phapi\Application'  => '/application/',
            'Phapi\Config'       => '/config/',
            'Phapi\Controllers'  => '/services/',
            'Phapi\Exceptions'   => '/controllers/',
            'Phapi\Models'       => '/models/',
            'Phapi\Services'     => '/exceptions/',
            'Phapi\Routes'       => '/routes/',
        ]
    ]
);