<?php

namespace Repos\Payments;

use Contracts\Payments\PaymentsContract;
// use Contracts\Calenders\CalendersContract;


class PayItRepo implements PaymentsContract
{

    private const MerchantName = "TEST.NajahNow";
    private const AuthenticationKey = "YW52DzYnU8E7PMyE";
    private const MasterKey = "JFC2idf18b55OMU5psbi2y9gLGqmD5Rq";
    private const MasterIV = "gQCXZ48EKku59XS7";
    private const OriginalEncrypted = "Uq85b/z3Yu4iQ0efflLzXxso1Wsg11FrA6ZZNDCBWsI=";
    private const OriginalDecrypted = "qP9mA1uEhLm8jqvk";
    private const Endpoint = "https://pay-it.mobi/globalpayit/pciglobal/WebForms/Payitcheckoutservice%20.aspx";
    private const ResponseUrl = "/";

    public function __construct()
    {
    }

    private function generateTransactionId($length)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }

    public function checkout($user_id, $data)
    {
        $paymentchannel = config('payment.' . env('PAYMENT_COUNTRY', 'EG') . '.payment_code'); /* kwknetonedc for knet */
        $isysid = $this->generateTransactionId(14);  /* Must be a unique number (atleast 12) for each request*/
        $amount = $data->amount * (1 - ($data->percent / 100));
        $description = $data->description;
        $description2 = $data->description;
        $tunnel = "isys";
        $currency = config('payment.' . env('PAYMENT_COUNTRY', 'EG') . '.currency_code');
        $language = "EN";
        $country = env('PAYMENT_COUNTRY', 'EG');
        $merchant_name = self::MerchantName;
        $akey = self::AuthenticationKey;
        $timestamp = time();
        $rnd = "";
        $original = self::OriginalEncrypted;
        $decryptedOriginal = self::OriginalDecrypted;
            /* the redirect url to receive payment notification */
        $merchantResponseUrl = self::ResponseUrl;
            /* payit checkout payment host */
        $host = self::Endpoint;

        $dataToComputeHash = $paymentchannel . "paymentchannel" . $isysid . "isysid" . $amount . "amount" . $timestamp . "timestamp" . $description . "description" . $rnd . "rnd" . $original . "original";
        $hash = strtoupper(hash_hmac("sha256", $dataToComputeHash, $decryptedOriginal));

        $params = [
            'paymentchannel' => $paymentchannel,
            'isysid' => $isysid,
            'amount' => $amount,
            'description' => $description,
            'description2' => $description2,
            'tunnel' => $tunnel,
            'currency' => $currency,
            'language' => $language,
            'country' => $country,
            'merchant_name' => $merchant_name,
            'akey' => $akey,
            'original' => $original,
            'hash' => $hash,
            'timestamp' => $timestamp,
            'Responseurl' => $merchantResponseUrl,
            "rnd" => $rnd,
            "user_id" => $user_id,
        ];
        return $host . '?' . http_build_query($params);

    }

    public function response($data)
    {
        $dataToComputeHash = self::MasterIV . "isysid=" . $isysid . "&result=" . $PaymentStatus2 . self::MasterIV;

        $computedHash = strtoupper(hash_hmac("sha256", $dataToComputeHash, self::OriginalDecrypted));

        if ($computedHash === $data->hash) {
            if ($data->result === "CAPTURED") {
                return true;
            }
            return false;
        }

        return null;
    }
}
