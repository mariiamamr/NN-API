<?php

/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 5/17/2018
 * Time: 11:30 AM
 */

namespace Contracts;

use App\SearchQuery;
use Contracts\Subjects\SubjectsContract;
use Contracts\Grades\GradesContract;
use Contracts\Languages\LanguagesContract;

interface SearchQueryContract
{
    public function __construct(SearchQuery $query, SubjectsContract $subject, GradesContract $grade, LanguagesContract $language);
    public function set($data);
}
