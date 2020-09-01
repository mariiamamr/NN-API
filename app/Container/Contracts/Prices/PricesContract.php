<?php
namespace App\Container\Contracts\Prices;

use App\Models\Option;

interface PricesContract
{
    public function __construct(Option $option);
    public function get();
    public function update($data);

}
