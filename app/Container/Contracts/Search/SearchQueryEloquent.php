<?php
namespace App\Container\Contracts\Search;

use App\Models\SearchQuery;
use App\Container\Contracts\Search\SearchQueryContract;
use App\Container\Contracts\Subjects\SubjectsContract;
use App\Container\Contracts\Grades\GradesContract;
use App\Container\Contracts\Languages\LanguagesContract;


class SearchQueryEloquent implements SearchQueryContract
{
    public function __construct(SearchQuery $query, SubjectsContract $subject, GradesContract $grade, LanguagesContract $lang)
    {
        $this->query = $query;
        $this->subject = $subject;
        $this->grade = $grade;
        $this->lang = $lang;
    }

    public function set($data)
    {
        $array_data = [];
        $subject = isset($data['subject']) ? $this->subject->get($data['subject']): null;
        $grade = isset($data['grade']) ? $this->grade->get($data['grade']) : null;
        $lang =  isset($data['lang']) ? $this->lang->get($data['lang']) : null;
        $price =  isset($data['price']) ? $data['price'] : null;
       
        $array_data['subject'] = isset($subject->title) ? $subject->title : null;
        $array_data['grade'] = isset($grade->title) ? $grade->title : null;
        $array_data['lang'] = isset($lang->title) ? $lang->title : null;
        $array_data['price'] = isset($price) ? $price : null;

        $json_data = json_encode($array_data);
        $query = $this->query->create(["query" => $json_data]);
        return $query;
    }
}
