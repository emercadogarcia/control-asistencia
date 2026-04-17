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
    public function turnos(Request $request)
    {
        $turnos = Turno::where('estado', 1)
            ->with('sucursal')
            ->orderBy('nombre')
            ->paginate(10);
        $sucursales = Sucursal::where('estado', 1)->get();
        $turnoEdit = $request->filled('edit')
            ? Turno::where('estado', 1)->findOrFail($request->integer('edit'))
            : null;

        return view('configuracion.turnos', compact('turnos', 'sucursales', 'turnoEdit'));
    }

    public function guardarTurno(Request $request, $id = null)
    {
        $validated = $request->validate([
            'sucursal_id' => 'required|exists:sucursal,id',
            'nombre' => 'required|string|max:80',
            'hora_entrada' => 'required|date_format:H:i',
            'hora_salida' => 'required|date_format:H:i',
            'tolerancia_min' => 'required|integer|min:0',
            'dias_semana' => 'required|array|min:1',
            'dias_semana.*' => 'required|in:lun,mar,mie,jue,vie,sab,dom',
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
        return redirect()->route('configuracion.turnos', ['edit' => $id]);
    }

    public function eliminarTurno($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->update(['estado' => 0]);

        return redirect()->route('configuracion.turnos')->with('success', 'Turno eliminado');
    }

    // CALENDARIO LABORAL
    public function calendario(Request $request)
    {
        $eventos = CalendarioLaboral::query()
            ->with('sucursal')
            ->orderByDesc('fecha')
            ->paginate(20);
        $sucursales = Sucursal::where('estado', 1)->get();
        $eventoEdit = $request->filled('edit')
            ? CalendarioLaboral::findOrFail($request->integer('edit'))
            : null;

        return view('configuracion.calendario', compact('eventos', 'sucursales', 'eventoEdit'));
    }

    public function guardarEvento(Request $request, $id = null)
    {
        $validated = $request->validate([
            'sucursal_id' => 'nullable|exists:sucursal,id',
            'fecha' => 'required|date',
            'es_feriado' => 'nullable|boolean',
            'descripcion' => 'nullable|string',
        ]);

        $validated['es_feriado'] = $request->boolean('es_feriado');

        if ($id) {
            $evento = CalendarioLaboral::findOrFail($id);
            $evento->update($validated);

            return redirect()->route('configuracion.calendario')->with('success', 'Evento actualizado');
        }

        CalendarioLaboral::create($validated);

        return redirect()->route('configuracion.calendario')->with('success', 'Evento creado');
    }

    public function editarEvento($id)
    {
        return redirect()->route('configuracion.calendario', ['edit' => $id]);
    }

    public function eliminarEvento($id)
    {
        $evento = CalendarioLaboral::findOrFail($id);
        $evento->delete();

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
