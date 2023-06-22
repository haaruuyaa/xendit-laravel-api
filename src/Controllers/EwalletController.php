<?php

namespace Haaruuyaa\XenditLaravelApi\Controllers;

use Haaruuyaa\XenditLaravelApi\Xendit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EwalletController extends Xendit
{
    public $endpoint,$charge,$void,$refund;
    public function __construct()
    {
        parent::__construct();
        $this->endpoint = 'ewallets/';
        $this->charge = 'charges';
        $this->void = 'void';
        $this->refund = 'refunds';
    }

    /**
     * @param $channel - Available channels are OVO, DANA, LINKAJA, SHOPEEPAY, ASTRAPAY, JENIUSPAY, SAKUKU
     * @param $body - Array of data consist of ['reference_id' => 'demo_1231o8u123', 'currency' => 'IDR','amount' => 2000,'checkout_method' => 'ONE_TIME_PAYMENT']
     * @return false|string
     */
    public function createPayment(string $channel, array $body)
    {
        $url = $this->config['url'].$this->endpoint.$this->charge;
        $arrayBody = $this->createPaymentBody($channel,$body);
        $jsonBody = json_encode($arrayBody);
        $response = $this->sendRequestApi('post',$url,$jsonBody);

        return $response;
    }

    public function createVoid($id)
    {
        $url = $this->config['url'].$this->endpoint.$this->charge.'/'.$id.'/'.$this->void;

        return $this->sendRequestApi('post',$url,null);
    }

    public function createRefund($id)
    {
        $url = $this->config['url'].$this->endpoint.$this->charge.'/'.$id.'/'.$this->refund;
        $amount = 2000;
        $reason = 'REQUESTED_BY_CUSTOMER';
        $body = json_encode($this->createrefundBody($amount,$reason));

        return $this->sendRequestApi('post',$url,$body);
    }

    public function getPaymentStatus($id)
    {
        $url = $this->config['url'].$this->endpoint.$this->charge.'/'.$id;
        return $this->sendRequestApi('get',$url,null);
    }

    private function createPaymentBody($channel, $body)
    {
        $rule = [
            'reference_id' => 'required|string|max:255',
            'currency' => [
                'required',
                Rule::in(['IDR','PHP']) # currently only accept IDR and PHP
            ],
            'amount' => 'required|integer',
            'checkout_method' => [
                'required',
                Rule::in(['ONE_TIME_PAYMENT'])
            ]
        ];

        $validator = Validator::make($body,$rule);

        if ($validator->fails()) {
            $error = $validator->errors();
            return response()->json($error);
        }

        switch ($channel) {
            case 'OVO' :
                $channelData = $this->ovoChannel();
                break;
            case 'DANA':
                $channelData = $this->danaChannel();
                break;
            case 'LINKAJA':
                $channelData = $this->linkAjaChannel();
                break;
            case 'SHOPEEPAY':
                $channelData = $this->shopeePayChannel();
                break;
            case 'ASTRAPAY':
                $channelData = $this->astraPayChannel();
                break;
            case 'JENIUSPAY':
                $channelData = $this->jeniusPayChannel('hafidzny');
                break;
            case 'SAKUKU':
                $channelData = $this->sakukuChannel();
                break;
            default:
                $channelData = [];
                break;
        }

        if(count($channelData) > 0) {
            if(isset($channelData['channel_code'])) {
                $body['channel_code'] = $channelData['channel_code'];
            }

            if(isset($channelData['channel_properties'])) {
                $body['channel_properties'] = $channelData['channel_properties'];
            }
        }

        return $body;
    }

    private function ovoChannel()
    {
        return [
            'channel_code' => 'ID_OVO',
            'channel_properties' => [
                'mobile_number' => '+6281282114064'
            ]
        ];
    }

    private function danaChannel()
    {
        return [
            'channel_code' => 'ID_DANA',
            'channel_properties' => [
                'success_redirect_url' => 'https://7554-3-0-150-168.ngrok-free.app/success_payment'
            ]
        ];
    }

    private function linkAjaChannel()
    {
        return [
            'channel_code' => 'ID_LINKAJA',
            'channel_properties' => [
                'success_redirect_url' => 'https://7554-3-0-150-168.ngrok-free.app/success_payment'
            ]
        ];
    }

    private function shopeePayChannel()
    {
        return [
            'channel_code' => 'ID_SHOPEEPAY',
            'channel_properties' => [
                'success_redirect_url' => 'https://7554-3-0-150-168.ngrok-free.app/success_payment'
            ]
        ];
    }

    private function astraPayChannel()
    {
        return [
            'channel_code' => 'ID_ASTRAPAY',
            'channel_properties' => [
                'success_redirect_url' => 'https://7554-3-0-150-168.ngrok-free.app/success_payment',
                'failure_redirect_url' => 'https://7554-3-0-150-168.ngrok-free.app/failed_payment'
            ]
        ];
    }

    private function jeniusPayChannel($cashTag)
    {
        return [
            'channel_code' => 'ID_JENIUSPAY',
            'channel_properties' => [
                'cashtag' => '$'.$cashTag
            ]
        ];
    }

    private function sakukuChannel()
    {
        return [
            'channel_code' => 'ID_SAKUKU',
            'channel_properties' => [
                'success_redirect_url' => 'https://7554-3-0-150-168.ngrok-free.app/success_payment'
            ]
        ];
    }

    private function createRefundBody($amount,$reason)
    {
        return [
            'amount' => $amount,
            'reason' => $reason,
        ];
    }

    public function paymentStatus(Request $request)
    {
        return response('',200);
    }
}