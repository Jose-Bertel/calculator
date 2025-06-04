<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'birthdate',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('usuario')
            ->logOnly(['name', 'email', 'birthdate'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Usuario fue {$eventName}");
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName === 'updated') {
            $old = $activity->properties->get('old', []);
            $new = $activity->properties->get('attributes', []);

            $activity->description = "Usuario actualizado. Cambios: " . json_encode([
                'antes' => $old,
                'después' => $new,
            ]);
        }
    }

    public function registrosImc()
    {
        return $this->hasMany(RegistroImc::class);
    }
}
