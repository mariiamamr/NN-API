<?php
namespace Contracts\Payments;

use Contracts\Calenders\CalendersContract;
use App\Order;

interface PaymentsContract
{
  public function __construct();
  public function checkout(Order $order);
  public function validate($data);
}
