<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroImc extends Model
{
    use HasFactory;

    protected $table = 'registro_imcs';

    protected $fillable = [
        'user_id',
        'peso',
        'estatura',
        'imc',
    ];

    // Relación con el modelo User
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}