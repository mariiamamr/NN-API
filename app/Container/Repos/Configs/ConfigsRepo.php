<?php
namespace Repos\Configs;

use Contracts\Configs\ConfigsContract;
use Contracts\Subjects\SubjectsContract;
use Contracts\Languages\LanguagesContract;
use Contracts\Grades\GradesContract;
use App\Http\Resources\Subject\SubjectCollections;
use App\Http\Resources\Grade\GradeCollections;
use App\Http\Resources\Language\LanguageCollections;
use Contracts\Options\OptionsContract;
use App\Http\Resources\Config\ConfigCollections;
use Contracts\Pages\PagesContract;



class ConfigsRepo implements ConfigsContract
{
  public function __construct(
    SubjectsContract $subject,
    LanguagesContract $lang,
    GradesContract $grade,
    OptionsContract $options,
    PagesContract $pages
  ) {
    $this->subject = $subject;
    $this->grade = $grade;
    $this->lang = $lang;
    $this->options = $options;
    $this->pages = $pages;
  }

  public function searchOptions()
  {
    $config = collect(new ConfigCollections($this->options->getByLabel('site_config')))->pluck('value', 'name')->toArray();
    // $prices = json_decode($this->options->getByLabel('teacher-prices')->first()->value);
    // $range = (array_merge($prices->group, $prices->individual));
    // sort($range);

    return [
      "subject" => collect(new SubjectCollections($this->subject->getAll()))->toArray() ?? [],
      "grad" => collect(new GradeCollections($this->grade->getAll()))->toArray() ?? [],
      "lang" => collect(new LanguageCollections($this->lang->getAll()))->toArray() ?? [],
      "site_config" => collect(new ConfigCollections($this->options->getByLabel('site_config')))->pluck('value', 'name')->toArray() ?? [],
      // "price_ranges" => [$range[0],$range[count($range)-1]],
      "pages" => $this->pages->getLinks(true)->toArray()
    ];
  }
}
