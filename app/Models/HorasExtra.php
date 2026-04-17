<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorasExtra extends Model
{
    use HasFactory;

    protected $table = 'horas_extras';
    protected $fillable = [
        'asistencia_id', 'minutos_extra', 'estado_aprobacion',
        'aprobado_por', 'fecha_aprobacion'
    ];
    protected $casts = ['fecha_aprobacion' => 'datetime'];
    public $timestamps = false;

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_APROBADO = 'aprobado';
    const ESTADO_RECHAZADO = 'rechazado';

    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class, 'asistencia_id');
    }

    public function aprobador()
    {
        return $this->belongsTo(Personal::class, 'aprobado_por');
    }
}
