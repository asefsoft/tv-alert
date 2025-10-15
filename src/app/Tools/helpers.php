<?php

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

function logMe($fileName, $log, $addDateToLog = true, $addDateToFileName = true): void {
    try {
        $prependDate = $addDateToLog ? now()->format('Y/m/d H:i:s').' ' : '';
        $fileNameDate = $addDateToFileName ? '-'.now()->toDateString() : '';
        File::append(
            storage_path()."/logs/{$fileName}".$fileNameDate.'.log',
            $prependDate.$log."\n"
        );
    } catch (Exception $e) {
        echo sprintf(
            "Error on log_me func: %s, log: %s<br>\n",
            $e->getMessage(),
            $log
        );
    }
}

function flashBanner($message, $type = 'success'): void
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

function ifProduction($productionValue, $notProductionValue): mixed
{
    return isProduction() ? $productionValue : $notProductionValue;
}

// highlighted texts has <hl> and </hl> in it
// so we will skip that tah
function strLimitHighlighted(
    string $text,
    int $length = 0,
    $end = '...'
): string {
    $addition = substr_count($text, '<hl>') * 3;
    $addition += substr_count($text, '</hl>') * 4;
    return strip_tags(Str::limit($text, $length + $addition, $end), ['hl']);
}

function logException($exception, $methodName, $extra = ''): void
{
    logError(sprintf(
        'Error on %s, %s, %s',
        $methodName,
        $extra,
        $exception->getMessage()
    ));
}

function logError($error, $level = 'warning'): void
{
    Log::log($level, $error);
}

function strLimit($text, $limit = 100, $end = '...'): string
{
    return Str::limit($text, $limit, $end);
}

function numFormat($n, $add_plus = true, $rtl = false) {
    $n_format = floor($n);
    $suffix   = '';
    $plus     = '';
    if ($n > 0 && $n < 1000) {
        // 1 - 999
        $n_format = floor($n);
        $suffix   = '';
        $plus     = '';
    }
    else if ($n >= 1000 && $n < 1000000) {
        // 1k-999k
        $n_format = floor($n / 1000);
        $suffix   = 'K';
        $plus     = '+';
    }
    else if ($n >= 1000000 && $n < 1000000000) {
        // 1m-999m
        $n_format = floor($n / 1000000);
        $suffix   = 'M';
        $plus     = '+';
    }
    else if ($n >= 1000000000 && $n < 1000000000000) {
        // 1b-999b
        $n_format = floor($n / 1000000000);
        $suffix   = 'G';
        $plus     = '+';
    }
    else if ($n >= 1000000000000) {
        // 1t+
        $n_format = floor($n / 1000000000000);
        $suffix   = 'T';
        $plus     = '+';
    }

    if ( ! $add_plus) {
        $plus = '';
    }

    $r = $rtl ? $plus . $n_format . $suffix : $n_format . $suffix . $plus;

    return ! empty($n_format . $suffix) ? $r : 0;
}
