<?php

namespace GoshipSdkPhp;

use GuzzleHttp\Client;
require_once(__DIR__ . '/Constants.php');

class Goship
{
    protected $options;
    protected $token;

    public function __construct($options) {
        $client = new Client([
            // Base URI is used with relative requests
            // 'base_uri' => BASE_API_LOCAL,
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $this->client = $client;
        $this->setUp($options);
    }
    /**
     * Setup
     *
     * @throws Exception
     */
    public function setUp($options)
    {
        $this->options = $options;
        if (isset($options['token'])) {
            $this->token = $options['token'];
        }
    }

    public function getAccessToken()
    {
        try {
            $request = $this->client->request('POST', BASE_API_LOCAL_LOGIN, ['form_params' => $this->options]);
            $data = json_decode($request->getBody()->getContents(), true);

            return $this->token = $data['token_type'] . ' ' . $data['access_token'];

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
        }
        return $errors;
    }

    public function getRates($param)
    {
        try {
            $request = $this->client->request('POST', BASE_API_LOCAL_RATES, ['form_params' => $param]);
            $data = json_decode($request->getBody()->getContents(), true);
            return $data;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
        }

        return $errors;
    }

    public function createShipment($param)
    {
        if (!$this->token) {
            $this->getAccessToken();
            return $this->requestShipment($param);
        } else {
            return $this->requestShipment($param);
        }
    }

    public function requestShipment ($param) {
        try {
            $request = $this->client->request('POST', BASE_API_LOCAL_CREATE_SHIPMENT, [
                'headers' => [
                    'Authorization' => $this->token,
                    'Accept'     => 'application/json'
                ],
                'allow_redirects' => false,
                'form_params' => $param
            ]);
            $data = json_decode($request->getBody()->getContents(), true);

            return $data;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
        }

        return $errors;
    }

}