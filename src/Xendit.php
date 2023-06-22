<?php

namespace Haaruuyaa\XenditLaravelApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as RequestAPI;

class Xendit
{
    public $header, $config, $client, $api_version;

    public function __construct()
    {
        $this->config = config('services.xendit_api');
        $this->header = [
            'Authorization' => 'Basic ' . $this->setApiKey($this->config['token']),
            'Content-Type' => 'application/json',
            'idempotency-key' => $this->generateUniqueString(),
            'api-version' => $this->config['version'],
        ];
        $this->client = new Client();
    }


    /**
     * @param $username
     * @param $password
     * @return string
     */
    private function setApiKey($username, $password = null)
    {
        $basic_auth = $username . ":" . $password;

        return base64_encode($basic_auth);
    }

    protected function sendRequestApi($method,$url,$body)
    {
        try {
            $request = new RequestAPI($method,$url,$this->header,$body);
            $sendRequest = $this->client->send($request);
            $response = $sendRequest->getBody()->getContents();
            \Log::debug('Response : '.$response);
            return $response;

        } catch (RequestException $ex) {
            $response = $ex->getResponse()->getBody()->getContents();
            \Log::debug('Response : '.$response);
            \Log::error($ex->getMessage().' => '.$ex->getFile().' : '.$ex->getLine());
            return $response;
        } catch (\Exception $ex) {
            \Log::error($ex->getMessage().' => '.$ex->getFile().' : '.$ex->getLine());
            return false;
        }
    }

    protected function generateUniqueString()
    {
        $randomString = str_replace('.','',uniqid('', true));

        $env = env('production') ? 'prd' : 'dev';

        return $env.'_'.substr($randomString, -32);
    }
}