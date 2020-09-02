<?php
namespace app\Container\Contracts\Contracts\Payments;

use app\Container\Contracts\Calenders\CalendersContract;
use App\Models\Order;

interface FawryContract
{
  public function __construct();
  public function checkout(Order $order);
}
