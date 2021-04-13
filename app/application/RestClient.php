<?php

namespace Phapi\Application;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Phalcon\Di;

class RestClient{

    private Client $client;
    private ?Di\DiInterface $di;

    public function __construct($serviceName)
    {
        $this->di = DI::getDefault();
        $this->client = new Client(['base_uri' => "http://$serviceName/"]);
    }

    public function get($route){
        $client = new Client([]);

        try {
            $request = $client->request('GET', $route, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->di->get('user')->token
                ]
            ]);
            $data = $request->getBody()->getContents();
            return json_decode($data);
        }
        catch (ClientException $e){
            return new ApiError([$e->getMessage()], [
                'status_code' => $e->getCode()
            ]);
        }
    }
}