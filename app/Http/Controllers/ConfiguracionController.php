<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\Turno;
use App\Models\CalendarioLaboral;
use App\Models\Asistencia;
use App\Models\HorasExtra;
use App\Models\AsignacionTurno;
use App\Models\Personal;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        return view('configuracion.index');
    }

    // SUCURSALES
    public function sucursales()
    {
        $sucursales = Sucursal::where('estado', 1)->paginate(10);
        return view('configuracion.sucursales', compact('sucursales'));
    }

    public function guardarSucursal(Request $request, $id = null)
    {
        $query = $id ? 'required|string|unique:sucursal,nombre,' . $id : 'required|string|unique:sucursal,nombre';
        
        $validated = $request->validate([
            'nombre' => $query,
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string',
        ]);

        if ($id) {
            $sucursal = Sucursal::findOrFail($id);
            $sucursal->update($validated);
            return redirect()->route('configuracion.sucursales')->with('success', 'Sucursal actualizada');
        } else {
            Sucursal::create($validated + ['estado' => 1]);
            return redirect()->route('configuracion.sucursales')->with('success', 'Sucursal creada');
        }
    }

    public function editarSucursal($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        return view('configuracion.sucursal-editar', compact('sucursal'));
    }

    public function eliminarSucursal($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update(['estado' => 0]);

        return redirect()->route('configuracion.sucursales')->with('success', 'Sucursal eliminada');
    }

    // TURNOS
    public function turnos()
    {
        $turnos = Turno::where('estado', 1)->with('sucursal')->paginate(10);
        $sucursales = Sucursal::where('estado', 1)->get();
        return view('configuracion.turnos', compact('turnos', 'sucursales'));
    }

    public function guardarTurno(Request $request, $id = null)
    {
        $validated = $request->validate([
            'sucursal_id' => 'required|exists:sucursals,id',
            'nombre' => 'required|string|max:80',
            'hora_entrada' => 'required|date_format:H:i',
            'hora_salida' => 'required|date_format:H:i',
            'tolerancia_min' => 'required|integer|min:0',
        ]);

        if ($id) {
            $turno = Turno::findOrFail($id);
            $turno->update($validated);
            return redirect()->route('configuracion.turnos')->with('success', 'Turno actualizado');
        } else {
            Turno::create($validated + ['estado' => 1]);
            return redirect()->route('configuracion.turnos')->with('success', 'Turno creado');
        }
    }

    public function editarTurno($id)
    {
        $turno = Turno::findOrFail($id);
        $sucursales = Sucursal::where('estado', 1)->get();
        return view('configuracion.turno-editar', compact('turno', 'sucursales'));
    }

    public function eliminarTurno($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->update(['estado' => 0]);

        return redirect()->route('configuracion.turnos')->with('success', 'Turno eliminado');
    }

    // CALENDARIO LABORAL
    public function calendario()
    {
        $eventos = CalendarioLaboral::where('estado', 1)->paginate(20);
        $sucursales = Sucursal::where('estado', 1)->get();
        return view('configuracion.calendario', compact('eventos', 'sucursales'));
    }

    public function guardarEvento(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'es_feriado' => 'nullable|boolean',
            'descripcion' => 'nullable|string',
        ]);

        CalendarioLaboral::create($validated + ['estado' => 1]);

        return redirect()->route('configuracion.calendario')->with('success', 'Evento creado');
    }

    public function eliminarEvento($id)
    {
        $evento = CalendarioLaboral::findOrFail($id);
        $evento->update(['estado' => 0]);

        return redirect()->route('configuracion.calendario')->with('success', 'Evento eliminado');
    }

    // RESET BASE DE DATOS
    public function reset(Request $request)
    {
        $password = $request->input('password');

        if (!Hash::check($password, auth()->user()->password)) {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        }

        // Limpiar datos respetando integridad
        Asistencia::truncate();
        HorasExtra::truncate();
        AsignacionTurno::truncate();
        Personal::truncate();
        CalendarioLaboral::truncate();
        Turno::truncate();
        Sucursal::truncate();

        return response()->json(['message' => 'Base de datos reiniciada']);
    }
}
