<?php
/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 4/18/2018
 * Time: 12:22 PM
 */

namespace Contracts\CreditCards;


use App\PaymentInfo;

interface CreditCardContract
{

    public function __construct(PaymentInfo $cards);

    public function get($id);

    public function set($data,$user_id);

    public function update($id,$data);

    public function delete($id);

}