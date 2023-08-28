<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

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
        'two_factor_recovery_codes',
        'two_factor_secret',
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

    // is user subscribed for a tvshow?
    public function hasSubscriptionFor(TVShow |int $tvshow) : bool {
        return in_array($tvshow->id ?? $tvshow, $this->subscriptions->pluck('id')->toArray());
    }

    // is authenticated user subscribed for given tvshow?
    // using static cache to prevent many db queries
    public static function isAuthUserSubscribedFor(TVShow |int $tvshow, bool $forceCheck = false): bool {
        static $authUserSubscriptions;

        if(! auth()->check())
            return false;

        if ($forceCheck || ! isset($authUserSubscriptions)) {
            $authUserSubscriptions = auth()->user()->subscriptions()->get()->pluck('id')->toArray();
        }

        return in_array($tvshow->id ?? $tvshow, $authUserSubscriptions);
    }

    // get recent shows that user is subscribed to
    // recent = aired recently or will be aired
    public function getRecentShows($page = 1, $perPage = 20): array {
        $shows = $this->subscriptions()->select('tvshow_id')->get()->pluck('tvshow_id')->toArray();
        return TVShow::getRecentShows($page, $perPage, $shows);
    }
}
