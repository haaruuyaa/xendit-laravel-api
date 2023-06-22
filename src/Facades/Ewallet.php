<?php

namespace Haaruuyaa\XenditLaravelApi\Facades;

use Illuminate\Support\Facades\Facade;

class Ewallet extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ewallet';
    }

    /**
     *  Create a Payment using selected payment method
     * @param $channel - Available channels are OVO, DANA, LINKAJA, SHOPEEPAY, ASTRAPAY, JENIUSPAY, SAKUKU
     * @param $body - Array of data consist of ['reference_id' => 'demo_1231o8u123', 'currency' => 'IDR','amount' => 2000,'checkout_method' => 'ONE_TIME_PAYMENT']
     * @return mixed
     */
    public static function createPayment($channel, $body)
    {
        return self::getFacadeRoot()->createPayment($channel, $body);
    }

    /**
     * Get the Status of the payment
     * @param $id - ID from Xendit Transaction
     * @return string - returned JSON string
     */
    public static function getPaymentStatus($id)
    {
        return self::getFacadeRoot()->getPaymentStatus($id);
    }

    /**
     * Send a void request to Xendit PG to void the charges
     * @param $id - ID from Xendit
     * @return string - returned JSON string
     */
    public static function createVoid($id)
    {
        return self::getFacadeRoot()->createVoid($id);
    }

    /**
     * Send a refund request to Xendit PG to void the charges
     * @param $id
     * @return string - returned JSON string
     */
    public static function createRefund($id)
    {
        return self::getFacadeRoot()->createRefund($id);
    }
}