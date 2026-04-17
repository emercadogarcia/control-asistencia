<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personal';
    protected $fillable = [
        'nombre', 'apellido', 'ci', 'email', 'telefono', 
        'tipo_personal', 'sucursal_id', 'estado', 'foto_url'
    ];
    public $timestamps = true;

    const CREATED_AT = 'creado_el';
    const UPDATED_AT = 'actualizado_el';

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function asignacionTurnos()
    {
        return $this->hasMany(AsignacionTurno::class, 'personal_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'personal_id');
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function getTurnoVigenteAttribute()
    {
        $hoy = now();
        return $this->asignacionTurnos()
            ->where('fecha_inicio', '<=', $hoy->toDateString())
            ->where(function($q) use ($hoy) {
                $q->whereNull('fecha_fin')
                  ->orWhere('fecha_fin', '>=', $hoy->toDateString());
            })
            ->with('turno')
            ->first();
    }
}
