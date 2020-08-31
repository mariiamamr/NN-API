<?php


namespace App\Container\Contracts\Search;

use App\Models\SearchQuery;
use App\Container\Contracts\Subjects\SubjectsContract;
use App\Container\Contracts\Grades\GradesContract;
use App\Container\Contracts\Languages\LanguagesContract;

interface SearchQueryContract
{
    public function __construct(SearchQuery $query, SubjectsContract $subject, GradesContract $grade, LanguagesContract $language);
    public function set($data);
}
