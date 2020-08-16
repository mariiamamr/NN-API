<?php

namespace Contracts\Ads;

use App\Ads;
use App\Option;

interface AdsContract
{

    public function __construct(Ads $ads, Option $option);

    public function get($id);

    public function getAll();

    public function set($data);

    public function update($id, $data);

    public function delete($id);

    public function paginate($search);

    public function latest();

    public function add_comment($id, $data);
}