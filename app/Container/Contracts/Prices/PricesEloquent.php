<?php
namespace App\Container\Contracts\Prices;

use App\Container\Contracts\Options\OptionsContract;
use App\Models\Option;
use App\Container\Contracts\Prices\PricesContract;
//use function GuzzleHttp\json_encode;

class PricesEloquent implements PricesContract
{
    private $pagination = 20;

    public function __construct(Option $option)
    {
        $this->option = $option;
    }

    public function get()
    {
        return $this->option->where('name', 'prices')->firstOrFail();
    }

    public function update($data)
    {
        return $this->get()->update([
            'value' => json_encode([
                "individual" => $data->price['individual'],
                "group" => $data->price['group'],
            ])
        ]);
    }
}
