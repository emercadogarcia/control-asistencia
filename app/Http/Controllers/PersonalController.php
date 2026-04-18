<?php

namespace App\Http\Controllers;

use App\Models\AsignacionTurno;
use App\Models\Personal;
use App\Models\Role;
use App\Models\Sucursal;
use App\Models\TipoPersonal;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PersonalController extends Controller
{
    public function index()
    {
        $search = request()->input('search');
        $sucursal_id = request()->input('sucursal_id');

        $query = Personal::query()->with(['sucursal', 'tipoPersonal', 'rol', 'turnoVigente.turno']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('apellido', 'like', "%$search%")
                  ->orWhere('ci', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($sucursal_id) {
            $query->where('sucursal_id', $sucursal_id);
        }

        $personals = $query->orderBy('nombre')->orderBy('apellido')->paginate(15);
        $sucursales = Sucursal::where('estado', 1)->orderBy('nombre')->get();
        $tiposPersonal = TipoPersonal::where('estado', 1)->orderBy('nombre')->get();
        $roles = Role::where('estado', 1)->orderBy('nombre')->get();
        $turnos = Turno::where('estado', 1)->with('sucursal')->orderBy('nombre')->get();
        $personalEdit = request()->filled('personal')
            ? Personal::with(['turnoVigente.turno'])->find(request()->integer('personal'))
            : null;

        return view('personal.index', compact(
            'personals',
            'sucursales',
            'search',
            'sucursal_id',
            'tiposPersonal',
            'roles',
            'turnos',
            'personalEdit'
        ));
    }

    public function crear()
    {
        $sucursales = Sucursal::where('estado', 1)->get();
        $tiposPersonal = TipoPersonal::where('estado', 1)->orderBy('nombre')->get();
        $roles = Role::where('estado', 1)->orderBy('nombre')->get();
        $turnos = Turno::where('estado', 1)->with('sucursal')->orderBy('nombre')->get();

        return view('personal.crear', compact('sucursales', 'tiposPersonal', 'roles', 'turnos'));
    }

    public function guardar(Request $request)
    {
        $validated = $this->validatePersonal($request);

        DB::transaction(function () use ($validated, $request) {
            $personal = Personal::create($this->extractPersonalData($validated) + ['estado' => 1]);
            $this->syncTurno($personal, $validated['turno_id'] ?? null, $validated['fecha_inicio_turno'] ?? null);
        });

        return redirect()->route('personal.index')->with('success', 'Personal creado exitosamente');
    }

    public function editar($id)
    {
        $personal = Personal::findOrFail($id);
        $sucursales = Sucursal::where('estado', 1)->get();
        $tiposPersonal = TipoPersonal::where('estado', 1)->orderBy('nombre')->get();
        $roles = Role::where('estado', 1)->orderBy('nombre')->get();
        $turnos = Turno::where('estado', 1)->with('sucursal')->orderBy('nombre')->get();

        return view('personal.editar', compact('personal', 'sucursales', 'tiposPersonal', 'roles', 'turnos'));
    }

    public function actualizar(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);
        $validated = $this->validatePersonal($request, $personal->id);

        DB::transaction(function () use ($personal, $validated) {
            $personal->update($this->extractPersonalData($validated));
            $this->syncTurno($personal->fresh(), $validated['turno_id'] ?? null, $validated['fecha_inicio_turno'] ?? null);
        });

        return redirect()->route('personal.index')->with('success', 'Personal actualizado');
    }

    public function asignarTurno(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);

        $validated = $request->validate([
            'turno_id' => 'required|exists:turno,id',
            'fecha_inicio_turno' => 'nullable|date',
        ]);

        DB::transaction(function () use ($personal, $validated) {
            $this->syncTurno($personal, $validated['turno_id'], $validated['fecha_inicio_turno'] ?? null);
        });

        return redirect()->route('personal.index')->with('success', 'Turno asignado correctamente');
    }

    public function eliminar($id)
    {
        $personal = Personal::findOrFail($id);
        $personal->update(['estado' => 0]);

        return redirect()->route('personal.index')->with('success', 'Personal desactivado');
    }

    private function validatePersonal(Request $request, ?int $personalId = null): array
    {
        $emailRule = 'required|email|unique:personal,email';
        $ciRule = 'required|string|unique:personal,ci';

        if ($personalId) {
            $emailRule .= ',' . $personalId;
            $ciRule .= ',' . $personalId;
        }

        return $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => $ciRule,
            'email' => $emailRule,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'fecha_contratacion' => 'required|date',
            'tipo_personal_id' => 'required|exists:tipo_personal,id',
            'rol_id' => 'required|exists:roles,id',
            'sucursal_id' => 'required|exists:sucursal,id',
            'turno_id' => 'nullable|exists:turno,id',
            'fecha_inicio_turno' => 'nullable|date',
        ]);
    }

    private function extractPersonalData(array $validated): array
    {
        return collect($validated)->except(['turno_id', 'fecha_inicio_turno'])->all();
    }

    private function syncTurno(Personal $personal, ?int $turnoId, ?string $fechaInicio = null): void
    {
        if (!$turnoId) {
            return;
        }

        $turno = Turno::where('estado', 1)->findOrFail($turnoId);

        if ((int) $turno->sucursal_id !== (int) $personal->sucursal_id) {
            throw ValidationException::withMessages([
                'turno_id' => 'El turno seleccionado debe pertenecer a la misma sucursal del personal.',
            ]);
        }

        $inicio = Carbon::parse($fechaInicio ?: ($personal->fecha_contratacion ?: now()->toDateString()))->toDateString();
        $finAnterior = Carbon::parse($inicio)->subDay()->toDateString();

        $asignacionActiva = AsignacionTurno::where('personal_id', $personal->id)
            ->where('estado', 1)
            ->where('fecha_inicio', '<=', $inicio)
            ->where(function ($query) use ($inicio) {
                $query->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>=', $inicio);
            })
            ->orderByDesc('fecha_inicio')
            ->first();

        if ($asignacionActiva && (int) $asignacionActiva->turno_id === $turno->id) {
            if ($asignacionActiva->fecha_inicio?->toDateString() !== $inicio) {
                $asignacionActiva->update(['fecha_inicio' => $inicio]);
            }

            return;
        }

        AsignacionTurno::where('personal_id', $personal->id)
            ->where('estado', 1)
            ->where(function ($query) use ($inicio) {
                $query->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>=', $inicio);
            })
            ->update([
                'fecha_fin' => $finAnterior,
                'estado' => 0,
            ]);

        AsignacionTurno::create([
            'personal_id' => $personal->id,
            'turno_id' => $turno->id,
            'fecha_inicio' => $inicio,
            'fecha_fin' => null,
            'estado' => 1,
        ]);
    }
}
