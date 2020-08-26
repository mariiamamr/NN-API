<?php
namespace App\Container\Contracts\Options;
use App\Container\Contracts\Grades\OptionsContract as OptionsContract;
use App\Models\Option;

class OptionsEloquent implements OptionsContract
{
    private $pagination = 20;

    public function __construct(Option $option)
    {
        $this->option = $option;
    }

    public function get($id)
    {
        return $this->option->findOrFail($id);
    }

    public function getByName($name)
    {
        if (is_array($name)) {
            return $this->option->whereIn('name', $name)->get();
        }

        return $this->option->where('name', $name)->firstOrFail();
    }

    public function getByLabel($label)
    {
        return $this->option->where('label', $label)->get();
    }

    public function getAll()
    {
        return $this->option->all();
    }


    public function update($data)
    {
        $data = $data->except('_method', '_token');
        //all records needs to 
        $records = $this->getByName(array_keys($data));

        foreach ($records as $item) {
            $value = $data[$item->name];
            $item->value = (is_array($value)) ? json_encode($value) : $value;
            $item->save();
        }

        return true;
    }

    public function getValueByName($name)
    {
        $option = $this->getByName($name);

        return (in_array($option->type, ['array', 'json'])) ? json_decode($option->value, true) : $option->value;
    }
}

