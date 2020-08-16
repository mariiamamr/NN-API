<?php
namespace Contracts\Prices;

use App\Option;

interface PricesContract
{
    public function __construct(Option $option);
    public function get();
    public function update($data);

}
