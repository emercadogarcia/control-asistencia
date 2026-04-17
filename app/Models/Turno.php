<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turno';
    protected $fillable = ['sucursal_id', 'nombre', 'hora_entrada', 'hora_salida', 'dias_semana', 'tolerancia_min', 'estado'];
    public $timestamps = true;

    const CREATED_AT = 'creado_el';
    const UPDATED_AT = 'actualizado_el';

    // Mutador para convertir nombre a mayúsculas
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

    public function setDiasSemanaAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['dias_semana'] = '{' . implode(',', $value) . '}';
            return;
        }

        $this->attributes['dias_semana'] = $value;
    }

    public function getDiasSemanaAttribute($value)
    {
        if (!$value) {
            return [];
        }

        $trimmed = trim($value, '{}');

        return $trimmed === '' ? [] : explode(',', $trimmed);
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
