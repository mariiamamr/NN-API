<?php
namespace App\Container\Contracts\PromoCodes;

use App\Models\Promocode;

interface PromoCodesContract
{
    public function __construct(Promocode $promocode);
    public function get($id);
    public function getAll($valid = true);
    public function getByCode($code);
    public function paginate($code = '');
    public function changeStatus($id, $status);
    public function set($data);
    public function update($id, $data);
    public function delete($id);
    public function validateCode($code,$first_request);
    public function use($code);
}
