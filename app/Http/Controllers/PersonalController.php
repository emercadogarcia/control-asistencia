<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    public function index()
    {
        $search = request()->input('search');
        $sucursal_id = request()->input('sucursal_id');

        $query = Personal::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('apellido', 'like', "%$search%")
                  ->orWhere('ci', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($sucursal_id) {
            $query->where('sucursal_id', $sucursal_id);
        }

        $personals = $query->with('sucursal')->paginate(15);
        $sucursales = Sucursal::where('estado', 1)->get();

        return view('personal.index', compact('personals', 'sucursales', 'search', 'sucursal_id'));
    }

    public function crear()
    {
        $sucursales = Sucursal::where('estado', 1)->get();
        return view('personal.crear', compact('sucursales'));
    }

    public function guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => 'required|string|unique:personal,ci',
            'email' => 'required|email|unique:personal,email',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'fecha_contratacion' => 'required|date',
            'tipo_personal' => 'required|in:empleado,supervisor,jefe',
            'sucursal_id' => 'required|exists:sucursal,id',
        ]);

        $personal = Personal::create($validated + ['estado' => 1]);

        return redirect()->route('personal.index')->with('success', 'Personal creado exitosamente');
    }

    public function editar($id)
    {
        $personal = Personal::findOrFail($id);
        $sucursales = Sucursal::where('estado', 1)->get();
        return view('personal.editar', compact('personal', 'sucursales'));
    }

    public function actualizar(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => 'required|string|unique:personal,ci,' . $id,
            'email' => 'required|email|unique:personal,email,' . $id,
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'fecha_contratacion' => 'required|date',
            'tipo_personal' => 'required|in:empleado,supervisor,jefe',
            'sucursal_id' => 'required|exists:sucursal,id',
        ]);

        $personal->update($validated);

        return redirect()->route('personal.index')->with('success', 'Personal actualizado');
    }

    public function eliminar($id)
    {
        $personal = Personal::findOrFail($id);
        $personal->update(['estado' => 0]);

        return redirect()->route('personal.index')->with('success', 'Personal desactivado');
    }
}
