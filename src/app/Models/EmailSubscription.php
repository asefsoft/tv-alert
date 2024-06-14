<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\UniqueConstraintViolationException;

class EmailSubscription extends Model
{
    use HasFactory;
    protected const MAX_TRIES = 5; // how many times try to send an email

    protected $fillable = ['target_day', 'is_sent'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // add subscription record for given user for today
    public static function addSubscriptionRecord(User $user): void
    {
        try {
            $user->emailSubscriptions()->create(['target_day' => now(), 'is_sent' => 0]);
        } catch (UniqueConstraintViolationException $e) {
            // record is already exists. that's ok
        }
    }

    public static function getTodayEmailSubscriptions(int $max = 10): Collection
    {
        return static::where('target_day', now()->format('Y-m-d'))
            ->with('user')
            ->where('is_sent', 0) // not sent emails
            ->where('tries', '<=', self::MAX_TRIES) // dont exceed tries
            ->take($max)
            ->get();
    }

    // add new try for an email
    public function addNewTry(bool $isSent): void
    {
        $this->tries++;
        $this->is_sent = $isSent;
        $this->save();
    }

    // making sure when saving target_day field it is in Y-m-d format
    protected function targetDay(): Attribute
    {
        return Attribute::set(function ($value) {
            return Carbon::parse($value)->format('Y-m-d');
        });
    }
}
