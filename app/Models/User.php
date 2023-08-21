<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // tv shows that user is subscribed to
    public function subscriptions(): BelongsToMany {
        return $this->belongsToMany(TVShow::class, 'subscriptions', 'user_id', 'tvshow_id');
    }

    public function addSubscription(TVShow | int $tvshow): bool {
        try {
            $this->subscriptions()->attach($tvshow->id ?? $tvshow);
        } catch (QueryException $e) {
            // skipping Duplicate entry exception which means subscription is already exist
            return str_contains($e->getMessage(), "Duplicate entry") ||
                   str_contains($e->getMessage(), "Integrity constraint violation");
        }

        return true;
    }

    public function removeSubscription(TVShow | int $tvshow): void {
        $this->subscriptions()->detach($tvshow->id ?? $tvshow);
    }
}
