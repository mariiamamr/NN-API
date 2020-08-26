<?php

namespace App\Container\Contracts\Payments;

use App\Container\Contracts\Payments\PaymentsContract;
use App\Models\Order;


class PaymentsEloquent implements PaymentsContract
{

    private const MerchantName      = "NajahNow";
    private const AuthenticationKey = "YW52DzYnU8E7PMyE";
    private const MasterKey         = "JFC2idf18b55OMU5psbi2y9gLGqmD5Rq";
    private const MasterIV          = "gQCXZ48EKku59XS7";
    private const OriginalEncrypted = "Uq85b/z3Yu4iQ0efflLzXxso1Wsg11FrA6ZZNDCBWsI=";
    private const OriginalDecrypted = "qP9mA1uEhLm8jqvk";
    private const Endpoint          = "https://pay-it.mobi/globalpayit/pciglobal/WebForms/Payitcheckoutservice%20.aspx";
    private const rnd               = "";

    public function __construct()
    {
        //$this->responseUrl       = route('front.payments.callback');
        $this->paymentchannel    = config('payment.' . env('PAYMENT_COUNTRY') . '.payment_code');
    }

    public function checkout(Order $order)
    {
        $requestDataToHash = $this->requestDataToHash($order);
        $hash = $this->hash($requestDataToHash);

        $params = [
            'paymentchannel' => $this->paymentchannel,
            'isysid'         => $order->unique_id,
            'amount'         => $order->amount,
            'description'    => $order->description,
            'description2'   => $order->description,
            'tunnel'         => config('payment.' . env('PAYMENT_COUNTRY') . '.tunnel'),
            'currency'       => config('payment.' . env('PAYMENT_COUNTRY') . '.currency_code'),
            'language'       => "EN",
            'country'        => config('payment.' . env('PAYMENT_COUNTRY') . '.country_code'),
            'merchant_name'  => self::MerchantName,
            'akey'           => self::AuthenticationKey,
            'original'       => self::OriginalEncrypted,
            'hash'           => $hash,
            'timestamp'      => time(),
            //'Responseurl'    => $this->responseUrl,
            "rnd"            => self::rnd,
            "user_id"        => $order->user_id,
        ];

        return self::Endpoint . '?' . http_build_query($params);
    }

    public function validate($response)
    {
        $responseDataToHash = $this->responseDataToHash($response);
        $hash               = $this->hash($responseDataToHash);

        if ($hash !== $response->hash) 
            return false;

        if ($response->result !== "CAPTURED")
            return false;

        return true;
    }

    private function requestDataToHash(Order $order)
    {
        return $this->paymentchannel . "paymentchannel" . $order->unique_id . "isysid" . $order->amount . "amount" . time() . "timestamp" . $order->description . "description" . self::rnd . "rnd" . self::OriginalEncrypted . "original";   
    }

    private function responseDataToHash($response) {
        return self::MasterIV . "isysid=" .  $response->isysid  . "&result=" . $response->result . self::MasterIV;
    }

    private function hash($data) {
        return strtoupper(hash_hmac("sha256", $data, self::OriginalDecrypted));
    }
}
