<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;

function title($title = null)
{
    return trans('frontend.app_title') . (empty($title) ?? ' | ' . $title);
}

function getModels(Type $var = null)
{
    return collect(\File::files(app_path()))->map(function ($file) {
        $class_name = trim(basename($file), '.php');
        return strtolower($class_name);
    });
}

function make_slug($title, $separator = '-')
{
    $flip = $separator == '-' ? '_' : '-';

    $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);

    // Remove all characters that are not the separator, letters, numbers, or whitespace.
    $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title));

    // Replace all separator characters and whitespace by a single separator
    $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);

    return trim($title, $separator);
}

function thumb($image, $size = null)
{
    try {
        if (count($image) == 0)
            return null;

        if ($image[0]->type == 'old_image')
            return asset('storage/' . $image[0]->title);

        if (empty($size))
            $url = $image[0]->title . '.' . $image[0]->ext;
        else
            $url = $image[0]->title . '_' . $size . '.' . $image[0]->ext;

        $exists = Storage::disk(env("STORAGE_DISK"))->has($url);
        if (!$exists)
            return null;

        return Storage::disk(env("STORAGE_DISK"))->url($url);
    } catch (\Exception $e) {
        return null;
    }
}

function singleThumb($image, $size = null)
{
    try {
        if (empty($size))
            $url = $image->title . '.' . $image->ext;
        else
            $url = $image->title . '_' . $size . '.' . $image->ext;

        $exists = Storage::disk(env("STORAGE_DISK"))->has($url);
        if (!$exists)
            return null;

        return Storage::disk(env("STORAGE_DISK"))->url($url);
    } catch (\Exception $e) {
        return null;
    }
}

function thumbByUrl($url)
{
    try {
        if (empty($url))
            return null;

        $exists = Storage::disk(env("STORAGE_DISK"))->has($url);

        if ($exists)
            return asset('uploads/' . $url);
        else
            return asset($url);
            
    } catch (\Exception $e) {
        return null;
    }
}

function getWeekly(array $weeks, $from = '01-12-2018')
{
    $intervals = [];
    $begin = Carbon::parse($from)->format('M Y');
    $end = Carbon::parse($from)->addMonth()->format('M Y');

    foreach ($weeks as $key => $value) {

        $array = iterator_to_array(
            new DatePeriod(
                Carbon::parse("first " . $value->on . " of " . $begin),
                CarbonInterval::week(),
                Carbon::parse("first " . $value->on . " of " . $end)
            )
        );

        $updated_array = array_values(array_filter(array_map(function ($item) use ($from, $value) {
            return ($item > Carbon::parse($from)) ? [
                "date" => $item->format('Y-m-d'),
                "time_from" => $value->time_from,
                "time_to" => $value->time_to,
                "lecture_id" => null,
                "timestamp" => strtotime($item->format('Y-m-d') . ' ' . $value->time_from)
            ] : null;
        }, $array)));

        if (count($updated_array) > 0) {
            $intervals = array_merge($intervals, $updated_array);
        }
    }

    return $intervals;
}

function reformat_date(array $days)
{ }

function teacherDisplayName($full_name)
{
    $display_name = "";

    $name_arr = explode(" ", $full_name);

    $display_name .= $name_arr[0];

    $display_name .= isset($name_arr[1]) ? " " . strtoupper(substr($name_arr[1], 0, 1)) . "." : "";

    return   $display_name;
}
