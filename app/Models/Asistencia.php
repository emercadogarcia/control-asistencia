<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencia';
    protected $fillable = [
        'personal_id', 'fecha', 'hora_entrada', 'hora_salida',
        'estado', 'observaciones', 'foto_url', 'ip_dispositivo'
    ];
    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime',
        'hora_salida' => 'datetime'
    ];
    public $timestamps = false;

    const ESTADO_PRESENTE = 'presente';
    const ESTADO_AUSENTE = 'ausente';
    const ESTADO_TARDANZA = 'tardanza';
    const ESTADO_PERMISO = 'permiso';

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function horasExtras()
    {
        return $this->hasMany(HorasExtra::class, 'asistencia_id');
    }
}
