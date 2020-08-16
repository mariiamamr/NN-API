<?php
namespace Contracts\Payments;

use Contracts\Calenders\CalendersContract;
use App\Order;

interface FawryContract
{
  public function __construct();
  public function checkout(Order $order);
}
