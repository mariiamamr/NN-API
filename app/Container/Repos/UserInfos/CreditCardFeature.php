<?php
namespace Repos\UserInfos;

class CreditCardFeature
{
    public function __construct($profile)
    {
        $this->profile = $profile;
    }

    public function set($data)
    {

        $payments = $this->profile->payment_info ?? [];

        return $this->profile->update(array_merge($payments, [
            'id' => uniqid(time()),
            'type' => config('api.credit_card_types.' . substr($data->card_number, 0, 1)) ?? "",
            'name_on_card' => $data->name_on_card,
            'expiry_date' => $data->exp_date,
            'card_number' => str_repeat('*', strlen($data->card_number) - 4) . substr(
                $data->card_number,
                strlen($data->card_number) - 4
            ),
            'is_default' => true
        ]));
    }

    public function delete($id)
    {
        $payments = $this->profile->payment_info;

        $ids = array_column($payments, 'id');
        if (!in_array($id, $ids)) {
            return null;
        }

        if (!$key = array_search($id, $ids)) {
            unset($payments[$id]);
            $payments = array_values($payments);
            return $this->profile->update('payment_info', $payments);
        }

        return null;
    }

    public function update($id)
    {

    }
}