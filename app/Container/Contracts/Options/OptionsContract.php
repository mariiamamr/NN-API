<?php
namespace Contracts\Options;

use App\Option;

interface OptionsContract
{
    public function __construct(Option $option);
    public function get($id);
    public function getAll();
    public function update($data);
    public function getByName($name);
    public function getByLabel($label);
}
