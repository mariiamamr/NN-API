<?php
/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 4/18/2018
 * Time: 12:22 PM
 */

namespace Repos\CreditCards;


use App\PaymentInfo;
use Contracts\CreditCards\CreditCardContract;

class CreditCardRepo implements CreditCardContract
{

    public function __construct(PaymentInfo $cards)
    {
        $this->cards = $cards;
    }

    public function get($id)
    {
        return $this->cards->find($id);
    }

    public function set($data, $user_id)
    {
        return $this->cards->create([
            'user_id'      => $user_id,
            'type'         => config('static.credit_card_types.' . substr($data->card_number, 0, 1)) ?? "",
            'name'         => $data->name,
            'last_digit'   => substr(
                    $data->card_number,
                    strlen($data->card_number) - 4
                ),
            'primary'      => $data->primary ?? 0,
            "unique_id"    => time()
        ]);
    }

    public function update($id, $data)
    {
        return $this->cards->where('id', $id)->update([
            'type'         => config('static.credit_card_types.' . substr($data->card_number, 0, 1)) ?? "",
            'name' => $data->name,
            'last_digit'  => str_repeat('*', strlen($data->card_number) - 4) . substr(
                    $data->card_number,
                    strlen($data->card_number) - 4
                ),
            'primary'   => $data->primary ?? 0,
        ]);
    }

    public function delete($id)
    {
        return $this->get($id)->delete();
    }
}