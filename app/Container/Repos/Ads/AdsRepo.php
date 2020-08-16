<?php

namespace Repos\Ads;

use App\Ads;
use App\Option;

class AdsRepo implements AdsContract
{

    public function __construct(Ads $ads, Option $option)
    {
        $this->ads    = $ads;
        $this->option = $option;
    }

    public function get($id)
    {
        return $this->ads->findOrFail($id);
    }

    public function getAll()
    {
        return $this->ads->all();
    }

    public function set($data)
    {
    }

    public function update($id, $data)
    {
    }

    public function delete($id)
    {
    }

    public function paginate($search)
    {
    }
}