<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\UniqueConstraintViolationException;

class EmailSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['target_day', 'is_sent'];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    // add subscription record for give user for today
    public static function addSubscriptionRecord(User $user) {
        try {
            $user->emailSubscriptions()->create(['target_day' => now(), 'is_sent' => 0]);
        } catch (UniqueConstraintViolationException $e) {
            // record is already exists. that's ok
        }
    }

    // making sure when saving target_day field it is in Y-m-d format
    protected function targetDay() : Attribute {
        return Attribute::set(function ($value) {
            return Carbon::parse($value)->format('Y-m-d');
        });
    }
}
