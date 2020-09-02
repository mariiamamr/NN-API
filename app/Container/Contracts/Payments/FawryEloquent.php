<?php

namespace App\Container\Contracts\Payments;

use App\Container\Contracts\Payments\FawryContract;
use App\Models\Order;


class FawryEloquent implements FawryContract
{

    private const Endpoint = "https://www.atfawry.com/ECommercePlugin/FawryPay.jsp";


    public function __construct()
    {
        $this->redirectToURL = route('front.fawry.callback');
        $this->failerPageUrl = route('front.payments.index');
        $this->merchantCode = env('MERCHANT_CODE');
        $this->secureHashKey = env('SECURE_HASH_KEY');
    }

    public function checkout(Order $order)
    {
        $orders = json_decode($order->lectures, 'Array');
        foreach ($orders as $key => $orderItem) {
            $orderItems[$key] = $orderItem['lecture_id'] . $orderItem['quantity'] . number_format($orderItem['price'], 2, '.', '');
            
        }

        $orderItems = implode('', $orderItems);
        $merchantCode = $this->merchantCode;
        $merchantRefNumber = $order->merchant_reference_number;
        $customerProfileId = $order->user_id;
        $expiry = $order->expiry_hours;
        $secureHashKey = $this->secureHashKey;
        $dataSignature = $merchantCode . $merchantRefNumber . $customerProfileId . $orderItems . $expiry . $secureHashKey;
        $signature = hash('sha256', $dataSignature);

        $chargeRequest = [];
        $chargeRequest['language'] = "en-eg";
        $chargeRequest['merchantCode'] = $this->merchantCode;
        $chargeRequest['merchantRefNumber'] = $order->merchant_reference_number;
        $chargeRequest['customer']['name'] = $order->students->full_name;
        $chargeRequest['customer']['mobile'] = $order->students->profile->phone;
        $chargeRequest['customer']['email'] = $order->students->email;
        $chargeRequest['customer']['customerProfileId'] = $order->user_id;
        $chargeRequest['order']['description'] = $order->description;
        $chargeRequest['order']['expiry'] = $order->expiry_hours;
        $chargeRequest['signature'] = $signature;

        foreach ($orders as $key => $orderItem) {
            $chargeRequest['order']['orderItems'][$key] = [
                'productSKU' => $orderItem['lecture_id'],
                'quantity' => $orderItem['quantity'],
                'price' => $orderItem['price'],
            ];
        }

        $url = self::Endpoint . '?chargeRequest=' . json_encode($chargeRequest) . '&successPageUrl=' .
            $this->redirectToURL . '&failerPageUrl=' . $this->failerPageUrl;

        return $url;
    }

}
