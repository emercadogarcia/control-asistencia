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
        'tipo_personal_id', 'rol_id', 'sucursal_id', 'estado', 'direccion',
        'fecha_nacimiento', 'fecha_contratacion'
    ];
    public $timestamps = true;

    const CREATED_AT = 'creado_el';
    const UPDATED_AT = 'actualizado_el';

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function tipoPersonal()
    {
        return $this->belongsTo(TipoPersonal::class, 'tipo_personal_id');
    }

    public function rol()
    {
        return $this->belongsTo(Role::class, 'rol_id');
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

    public function turnoVigente()
    {
        $hoy = now()->toDateString();

        return $this->hasOne(AsignacionTurno::class, 'personal_id')
            ->where('estado', 1)
            ->where('fecha_inicio', '<=', $hoy)
            ->where(function ($q) use ($hoy) {
                $q->whereNull('fecha_fin')
                  ->orWhere('fecha_fin', '>=', $hoy);
            })
            ->latestOfMany('fecha_inicio');
    }
}
