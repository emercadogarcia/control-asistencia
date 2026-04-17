<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turno';
    protected $fillable = ['sucursal_id', 'nombre', 'hora_entrada', 'hora_salida', 'dias_semana', 'tolerancia_min', 'estado'];
    protected $casts = ['dias_semana' => 'array'];
    public $timestamps = true;

    const CREATED_AT = 'creado_el';
    const UPDATED_AT = 'actualizado_el';

    // Mutador para convertir nombre a mayúsculas
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionTurno::class, 'turno_id');
    }
}
