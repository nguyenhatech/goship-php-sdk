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
    protected $workspace = 'production';
    protected $version = 'v1';

    public function __construct($options) {
        $this->setUp($options);
        if (isset($options['workspace']) && isset($options['version'])) {
            $this->workspace = $options['workspace'];
            $this->version = $options['version'];
        }

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->generateBaseUrl(),
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $this->client = $client;
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

    public function getToken()
    {
        $errors = new ValidateAuth;
        if ($errors->validate($this->options)) {
            try {
                $request = $this->client->request('POST', "{$this->generateLoginUrl()}", ['form_params' => $this->options]);
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
            if (!$this->token) {
                $this->getToken();
                return $this->sendGetRateRequest($param);
            } else {
                return $this->sendGetRateRequest($param);
            }
        }

    }

    public function createShipment($param)
    {
        $this->shipment['rate'] = $param['id'];
        $errors = new ValidateShipment;
        if ($errors->validate($this->shipment)) {
            if (!$this->token) {
                $this->getToken();
                return $this->sendCreateShipmentResquest($this->shipment);
            } else {
                return $this->sendCreateShipmentResquest($this->shipment);
            }
        }
    }

    public function detailShipment($id)
    {
        if ($id != '') {
            if (!$this->token) {
                $this->getToken();
                return $this->sendDetailShipmentResquest($id);
            } else {
                return $this->sendDetailShipmentResquest($id);
            }
        }
    }

    public function trackShipment($id)
    {
        if ($id != '') {
            if (!$this->token) {
                $this->getToken();
                return $this->sendDetailShipmentResquest($id);
            } else {
                return $this->sendDetailShipmentResquest($id);
            }
        }
    }

    public function sendCreateShipmentResquest ($param) {
        try {
            $request = $this->client->request('POST', 'ext_v1/shipment/create', [
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

    public function generateBaseUrl() {
        if ($this->workspace === 'sandbox') {
            return 'https://sandbox.goship.io/api/';
        } else if ($this->workspace === 'dev') {
            return 'http://goship.dev/api/';
        } else {
            return 'https://api.goship.io/api/';
        }
    }

    public function generateLoginUrl() {
        return "{$this->version}/login";
    }

    public function sendGetRateRequest($param) {
        try {
            $request = $this->client->request('POST',"ext_{$this->version}/shipment/rates", [
                'headers' => [
                    'Authorization' => $this->token,
                    'Accept'     => 'application/json'
                ],
                'allow_redirects' => false,
                'form_params' => ['shipment' => $param]
            ]);
            $data = json_decode($request->getBody()->getContents(), true);
            return $data['data'];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
        }
        return $errors;
    }

    public function sendDetailShipmentResquest ($id) {
        try {
            $request = $this->client->request('GET',"ext_{$this->version}/shipment/info/{$id}", [
                'headers' => [
                    'Authorization' => $this->token,
                    'Accept'     => 'application/json'
                ],
                'allow_redirects' => false
            ]);
            $data = json_decode($request->getBody()->getContents(), true);
            return $data['data'];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
        }
        return $errors;
    }

    public function sendTrackShipmentResquest ($id) {
        try {
            $request = $this->client->request('GET',"ext_{$this->version}/shipment/track/{$id}", [
                'headers' => [
                    'Authorization' => $this->token,
                    'Accept'     => 'application/json'
                ],
                'allow_redirects' => false
            ]);
            $data = json_decode($request->getBody()->getContents(), true);
            return $data['data'];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
        }
        return $errors;
    }
}