<?php
namespace Contracts\Configs;

use Contracts\Subjects\SubjectsContract;
use Contracts\Languages\LanguagesContract;
use Contracts\Grades\GradesContract;
use Contracts\Options\OptionsContract;
use Contracts\Pages\PagesContract;



interface ConfigsContract
{
  public function __construct(
    SubjectsContract $subject,
    LanguagesContract $lang,
    GradesContract $grade,
    OptionsContract $options,
    PagesContract $pages
  );

  public function searchOptions();
}