<?php
namespace Repos\Prices;

use Contracts\Options\OptionsContract;
use App\Option;
use Contracts\Prices\PricesContract;
use function GuzzleHttp\json_encode;

class PricesRepo implements PricesContract
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
