<?php
namespace App\Container\Contracts\Payments;

use App\Container\Contracts\Calenders\CalendersContract;
use App\Models\Order;

interface FawryContract
{
  public function __construct();
  public function checkout(Order $order);
}
