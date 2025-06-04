<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class RegistroImc extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'registro_imcs';

    protected $fillable = [
        'user_id',
        'peso',
        'estatura',
        'imc',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('registro_imc')
            ->logOnly(['peso', 'estatura', 'imc'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Registro IMC fue {$eventName}");
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName === 'updated') {
            $old = $activity->properties->get('old', []);
            $new = $activity->properties->get('attributes', []);

            $activity->description = "Registro IMC actualizado. Cambios: " . json_encode([
                'antes' => $old,
                'después' => $new,
            ]);
        }
    }

    // Relación con el modelo User
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
