<?php
namespace App\Container\Contracts\PromoCodes;

use App\Container\Contracts\PromoCodes\PromoCodesContract;
use App\Models\Promocode;

class PromoCodesEloquent implements PromoCodesContract
{
    private $number_of_pages = 1;

    public function __construct(Promocode $promocode)
    {
        $this->promo_code = $promocode;
        $this->number_of_pages = config('static.pagination.rowsPerPage');
    }

    public function get($id)
    {
        return $this->promo_code->findOrFail($id);
    }

    public function getByCode($code)
    {
        return $this->promo_code->where('code', $code)->first();
    }

    public function paginate($code = "")
    {
        return $this->promo_code->where('active', 1)->where(function ($query) use ($code) {
            if ($code != '') {
                return $query->where('code', 'like', '%' . $code . '%');
            }
        })->paginate($this->number_of_pages);
    }

    public function getAll($active = true)
    {
        return ($active) ? $this->promo_code->where('valid_from', '<=', date('Y-m-d'))
            ->where('valid_to', '>=', date('Y-m-d'))->where('active', 1)->get() : $this->promo_code->all();
    }

    public function update($id, $data)
    {
        return $this->get($id)->update([
            'code'         => $data->code,
            'valid_from'   => $data->valid_from,
            'valid_to'     => $data->valid_to,
            'percent'      => $data->percent,
            'usage_limit'  => $data->usage_limit ?? 0,
            'new_students' => $data->new_students ?? false
        ]);
    }

    public function set($data)
    {
        return $this->promo_code->create([
            'code'         => $data->code,
            'valid_from'   => $data->valid_from,
            'valid_to'     => $data->valid_to,
            'percent'      => $data->percent,
            'active'       => 1,
            'usage_limit'  => $data->usage_limit ?? 0,
            'new_students' => $data->new_students ?? false
        ]);
    }

    public function delete($id)
    {
        return $this->get($id)->delete();
    }

    public function changeStatus($id, $status)
    {
        return $this->get($id)->update([
            'active' => $status
        ]);
    }

    public function validateCode($code, $first_request)
    {
        $promoCode = $this->getByCode($code);
        if (!$promoCode) {
            return null;
        }

        if (strtotime($promoCode->valid_from) >= strtotime('now') || strtotime($promoCode->valid_to) >= strtotime('now')) {
            if ($promoCode->usage_limit != null) {
                return ($promoCode->usage_limit != 0) ? $promoCode : false;
            }
            if ($promoCode->new_students != 0) {
                return ($first_request == 0) ? $promoCode : false;
            } 

            return $promoCode;
        }

        return false;
    }

    public function use($code)
    {
        $code = $this->getByCode($code);
        if (!$code) {
            return 0;
        }

        $code->decrement('usage_limit', 1);

        return $code->percent;
    }
}
