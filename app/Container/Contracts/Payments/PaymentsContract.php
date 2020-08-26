<?php
namespace App\Container\Contracts\Payments;

use App\Container\Contracts\Calenders\CalendersContract;
use App\Models\Order;

interface PaymentsContract
{
  public function __construct();
  public function checkout(Order $order);
  public function validate($data);
}
