<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursal';
    protected $fillable = ['nombre', 'descripcion', 'direccion', 'ciudad', 'telefono', 'estado'];
    public $timestamps = true;

    const CREATED_AT = 'creado_el';
    const UPDATED_AT = 'actualizado_el';

    // Mutadores para convertir a mayúsculas
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = $value ? strtoupper($value) : null;
    }

    public function setCiudadAttribute($value)
    {
        $this->attributes['ciudad'] = $value ? strtoupper($value) : null;
    }

    public function setTelefonoAttribute($value)
    {
        $this->attributes['telefono'] = $value ? strtoupper($value) : null;
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class, 'sucursal_id');
    }

    public function personals()
    {
        return $this->hasMany(Personal::class, 'sucursal_id');
    }
}
