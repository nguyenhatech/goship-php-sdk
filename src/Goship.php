<?php

namespace GoshipSdkPhp;

use GuzzleHttp\Client;
use GoshipSdkPhp\Validate\ValidateAuth;
use GoshipSdkPhp\Validate\ValidateRate;
use GoshipSdkPhp\Validate\ValidateShipment;

class Goship
{
    protected $options;
    protected $token;
    protected $shipment;

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
        $errors = new ValidateAuth;
        if ($errors->validate($this->options)) {
            try {
                $request = $this->client->request('POST', BASE_API_LOCAL_LOGIN, ['form_params' => $this->options]);
                $data = json_decode($request->getBody()->getContents(), true);

                return $this->token = $data['token_type'] . ' ' . $data['access_token'];

            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
            }
            return $errors;
        }
    }

    public function getRates($param)
    {
        $errors = new ValidateRate;
        if ($errors->validate($param)) {
            $this->shipment = $param;
            try {
                $request = $this->client->request('POST', BASE_API_LOCAL_RATES, ['form_params' => ['shipment' => $param]]);
                $data = json_decode($request->getBody()->getContents(), true);
                return $data['data'];
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
            }

            return $errors;
        }

    }

    public function createShipment($param)
    {
        $this->shipment['rate'] = $param;
        $errors = new ValidateShipment;
        if ($errors->validate($this->shipment)) {
            if (!$this->token) {
                $this->getAccessToken();
                return $this->requestShipment($this->shipment);
            } else {
                return $this->requestShipment($this->shipment);
            }
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
                'form_params' => ['shipment' => $param]
            ]);
            $data = json_decode($request->getBody()->getContents(), true);

            return $data;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
        }

        return $errors;
    }

}