<?php
namespace App\Container\Contracts\Options;

use App\Models\Option;

interface OptionsContract
{
    public function __construct(Option $option);
    public function get($id);
    public function getAll();
    public function update($data);
    public function getByName($name);
    public function getByLabel($label);
}
