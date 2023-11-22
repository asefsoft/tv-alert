<?php

use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;

function sendMail()
{
    $mail = new Mailable();
    $mail->subject = 'Test from here';
    $mail->html("this is just a test email\n yes this is!");

    Mail::to('asefsoft@gmail.com')->send($mail);
}

function logMe($fileName, $log, $addDateToLog = true, $addDateToFileName = true)
{
    try {
        $prependDate = $addDateToLog ? now()->format('Y/m/d H:i:s').' ' : '';
        $fileNameDate = $addDateToFileName ? '-'.now()->toDateString() : '';
        File::append(
            storage_path()."/logs/{$fileName}".$fileNameDate.'.log',
            $prependDate.$log."\n"
        );
    } catch (Exception $e) {
        echo sprintf("Error on log_me func: %s, log: %s<br>\n", $e->getMessage(), $log);
    }
}

function flashBanner($message, $type = 'success')
{
    request()->session()->flash('flash.banner', $message);
    request()->session()->flash('flash.bannerStyle', $type);
}

function isAdmin(): bool
{
    static $isAdmin;

    $booted = app()->isBooted();

    if (! empty($isAdmin) && $booted) {
        return $isAdmin;
    }
    // todo: add this functionality
    return $isAdmin = auth()->user() !== null && auth()->user()->can('manage');
}

function isLocal(): bool
{
    return app()->environment('local');
}

function isTesting(): bool
{
    return app()->environment('testing');
}

function isProduction(): bool
{
    return app()->environment('production');
}

function ifProduction($productionValue, $notProductionValue)
{
    return isProduction() ? $productionValue : $notProductionValue;
}

function numberFormatShort($n, $add_plus = true, $rtl = false)
{
    $n_format = floor($n);
    $suffix = '';
    $plus = '';
    if ($n > 0 && $n < 1000) {
        // 1 - 999
        $n_format = floor($n);
        $suffix = '';
        $plus = '';
    } elseif ($n >= 1000 && $n < 1000000) {
        // 1k-999k
        $n_format = floor($n / 1000);
        $suffix = 'K';
        $plus = '+';
    } elseif ($n >= 1000000 && $n < 1000000000) {
        // 1m-999m
        $n_format = floor($n / 1000000);
        $suffix = 'M';
        $plus = '+';
    } elseif ($n >= 1000000000 && $n < 1000000000000) {
        // 1b-999b
        $n_format = floor($n / 1000000000);
        $suffix = 'G';
        $plus = '+';
    } elseif ($n >= 1000000000000) {
        // 1t+
        $n_format = floor($n / 1000000000000);
        $suffix = 'T';
        $plus = '+';
    }

    if (! $add_plus) {
        $plus = '';
    }

    $r = $rtl ? $plus.$n_format.$suffix : $n_format.$suffix.$plus;

    return ! empty($n_format.$suffix) ? $r : 0;
}

function getDateString($date, $type = 'remaining', $format = 'd F, Y H:i:s')
{
    if (empty($date)) {
        return '';
    }

    if (! $date instanceof Carbon) {
        $date = Carbon::parse($date);
    }

    switch ($type) {
        default:
        case 'remaining':
            return $date->diffForHumans();
        case 'jalali':
        case 'persian':
            return jalalianDate($date, $format);
        case 'miladi':
        case 'jeorgian':
            return $date->format($format);
    }
}

// highlighted texts has <hl> and </hl> in it
// so we will skip that tah
function strLimitHighlighted(string $text, int $length = 0, $end = '...'): string
{
    $addition = substr_count($text, '<hl>') * 3;
    $addition += substr_count($text, '</hl>') * 4;
    return strip_tags(Str::limit($text, $length + $addition, $end), ['hl']);
}

function jalalianDate($date, $format = 'd F, Y', $default = ''): string
{
    if (empty($date)) {
        return $default;
    }

    return Jalalian::forge($date)->format($format);
}

function onlyFields($models, array $fields, $limit_string = 80, array $headers = [], array $footers = [])
{
    //        $d=$models->pluck('video.local_video.file_path');
    $strings = $models->map(function ($model) use ($limit_string, $fields) {
        $string = [];

        foreach ($fields as $field) {
            // nested relation value
            $segments = explode('.', $field);

            if (count($segments) > 1) {
                //                    $relation_value = $model->getAttribute($segments[0]);
                //                    $value = $relation_value instanceof Model ? $relation_value->getAttribute($segments[1]) : null;
                $value = data_get($model, $segments);
            } else {
                $value = $model->$field;
            }

            if ($value instanceof Carbon && false) {
                $value = $value->diffForHumans();
            }

            $string[] = sprintf("%s: '%s'", array_reverse($segments)[0], Str::limit($value, $limit_string));
        }

        return implode(', ', $string);
    });

    $strings = collect($headers)->merge($strings);
    return $strings->merge($footers);
}

function logException($exception, $methodName, $extra = '')
{
    logError(sprintf('Error on %s, %s, %s', $methodName, $extra, $exception->getMessage()));
}

function logError($error, $level = 'warning'): void
{
    Log::log($level, $error);
}

function strLimit($text, $limit = 100, $end = '...')
{
    return Str::limit($text, $limit, $end);
}

function loadTime()
{
    $load_time = getTook();
    echo sprintf(
        "<took style='display: none'>%.2f sec</took>\n<query style='display: none'>%s ms, count: %s, slow: %s</query>",
        $load_time,
        number_format($GLOBALS['STAT_QUERY_TIME'] ?? -1),
        number_format($GLOBALS['STAT_QUERY_COUNT'] ?? -1),
        number_format($GLOBALS['STAT_QUERY_COUNT_SLOW'] ?? -1)
    );

    // slow?
    if ($load_time >= 2) {
        $user = auth()->check() ? 'user: '.auth()->user()->name.', ' : '';

        $q = sprintf(
            "Slow Page Load, time: %.2f sec, %s%s\nSLOW_QUERY_COUNT: %s\nQUERY_TIME: %s ms.\n",
            $load_time,
            $user,
            rawurldecode(request()->fullUrl()),
            $GLOBALS['STAT_QUERY_COUNT_SLOW'] ?? -1,
            $GLOBALS['STAT_QUERY_TIME'] ?? -1
        );

        Log::warning($q);
    }
}

function getTook(): float
{
    return microtime(true) - LARAVEL_START;
}
