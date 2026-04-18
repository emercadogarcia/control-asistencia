<?php

namespace App\Services;

use App\Models\Asistencia;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ReporteAsistenciaService
{
    private const JORNADA_MINUTOS = 480;
    private const HORA_ENTRADA_ESPERADA = '08:00:00';

    public function getReport(string $type, array $filters = []): array
    {
        $filters = $this->normalizeFilters($filters);

        return match ($type) {
            'asistencia_diaria' => $this->buildAsistenciaDiariaReport($filters),
            'atrasos' => $this->buildAtrasosReport($filters),
            'horas_trabajadas' => $this->buildHorasTrabajadasReport($filters),
            'horas_extra' => $this->buildHorasExtraReport($filters),
            'inasistencias' => $this->buildInasistenciasReport($filters),
            default => throw new InvalidArgumentException('Tipo de reporte no soportado'),
        };
    }

    private function buildAsistenciaDiariaReport(array $filters): array
    {
        $query = $this->baseAsistenciaQuery($filters)
            ->orderByDesc('asistencia.fecha')
            ->orderBy('personal.nombre')
            ->orderBy('personal.apellido');

        $headings = ['ID', 'Personal', 'CI', 'Sucursal', 'Fecha', 'Hora Entrada', 'Hora Salida', 'Estado'];

        return $this->buildResponse(
            title: 'Reporte de Asistencia Diaria',
            filenamePrefix: 'reporte-asistencia-diaria',
            filters: $filters,
            headings: $headings,
            query: $query,
            transformer: fn ($row) => [
                'id' => $row->id,
                'personal' => $row->personal,
                'ci' => $row->ci,
                'sucursal' => $row->sucursal,
                'fecha' => $this->formatDate($row->fecha),
                'hora_entrada' => $this->formatDateTime($row->hora_entrada),
                'hora_salida' => $this->formatDateTime($row->hora_salida),
                'estado' => $row->estado,
            ]
        );
    }

    private function buildAtrasosReport(array $filters): array
    {
        $query = $this->baseAsistenciaQuery($filters)
            ->whereNotNull('asistencia.hora_entrada')
            ->whereTime('asistencia.hora_entrada', '>', self::HORA_ENTRADA_ESPERADA)
            ->selectRaw(
                "GREATEST(
                    ROUND(EXTRACT(EPOCH FROM ((asistencia.hora_entrada)::time - TIME '" . self::HORA_ENTRADA_ESPERADA . "')) / 60),
                    0
                ) as minutos_atraso"
            )
            ->orderByDesc('asistencia.fecha')
            ->orderByDesc('asistencia.hora_entrada');

        $headings = ['ID', 'Personal', 'CI', 'Sucursal', 'Fecha', 'Hora Entrada', 'Minutos Atraso', 'Estado'];

        return $this->buildResponse(
            title: 'Reporte de Atrasos',
            filenamePrefix: 'reporte-atrasos',
            filters: $filters,
            headings: $headings,
            query: $query,
            transformer: fn ($row) => [
                'id' => $row->id,
                'personal' => $row->personal,
                'ci' => $row->ci,
                'sucursal' => $row->sucursal,
                'fecha' => $this->formatDate($row->fecha),
                'hora_entrada' => $this->formatDateTime($row->hora_entrada),
                'minutos_atraso' => (int) $row->minutos_atraso,
                'estado' => $row->estado,
            ]
        );
    }

    private function buildHorasTrabajadasReport(array $filters): array
    {
        $query = $this->baseAsistenciaConMinutosTrabajadosQuery($filters)
            ->whereNotNull('asistencia.hora_entrada')
            ->whereNotNull('asistencia.hora_salida')
            ->orderByDesc('asistencia.fecha')
            ->orderBy('personal.nombre')
            ->orderBy('personal.apellido');

        $headings = ['ID', 'Personal', 'CI', 'Sucursal', 'Fecha', 'Hora Entrada', 'Hora Salida', 'Minutos Trabajados', 'Horas Trabajadas'];

        return $this->buildResponse(
            title: 'Reporte de Horas Trabajadas',
            filenamePrefix: 'reporte-horas-trabajadas',
            filters: $filters,
            headings: $headings,
            query: $query,
            transformer: fn ($row) => [
                'id' => $row->id,
                'personal' => $row->personal,
                'ci' => $row->ci,
                'sucursal' => $row->sucursal,
                'fecha' => $this->formatDate($row->fecha),
                'hora_entrada' => $this->formatDateTime($row->hora_entrada),
                'hora_salida' => $this->formatDateTime($row->hora_salida),
                'minutos_trabajados' => (int) $row->minutos_trabajados,
                'horas_trabajadas' => $this->formatMinutes((int) $row->minutos_trabajados),
            ]
        );
    }

    private function buildHorasExtraReport(array $filters): array
    {
        $query = $this->baseAsistenciaConMinutosTrabajadosQuery($filters)
            ->whereNotNull('asistencia.hora_entrada')
            ->whereNotNull('asistencia.hora_salida')
            ->whereRaw('ROUND(EXTRACT(EPOCH FROM (asistencia.hora_salida - asistencia.hora_entrada)) / 60) > ?', [self::JORNADA_MINUTOS])
            ->selectRaw(
                'GREATEST(
                    ROUND(EXTRACT(EPOCH FROM (asistencia.hora_salida - asistencia.hora_entrada)) / 60) - ?,
                    0
                ) as minutos_extra',
                [self::JORNADA_MINUTOS]
            )
            ->orderByDesc('asistencia.fecha')
            ->orderByDesc('minutos_extra');

        $headings = ['ID', 'Personal', 'CI', 'Sucursal', 'Fecha', 'Hora Entrada', 'Hora Salida', 'Horas Trabajadas', 'Minutos Extra', 'Horas Extra'];

        return $this->buildResponse(
            title: 'Reporte de Horas Extra',
            filenamePrefix: 'reporte-horas-extra',
            filters: $filters,
            headings: $headings,
            query: $query,
            transformer: fn ($row) => [
                'id' => $row->id,
                'personal' => $row->personal,
                'ci' => $row->ci,
                'sucursal' => $row->sucursal,
                'fecha' => $this->formatDate($row->fecha),
                'hora_entrada' => $this->formatDateTime($row->hora_entrada),
                'hora_salida' => $this->formatDateTime($row->hora_salida),
                'horas_trabajadas' => $this->formatMinutes((int) $row->minutos_trabajados),
                'minutos_extra' => (int) $row->minutos_extra,
                'horas_extra' => $this->formatMinutes((int) $row->minutos_extra),
            ]
        );
    }

    private function buildInasistenciasReport(array $filters): array
    {
        $query = $this->baseInasistenciasQuery($filters)
            ->orderByDesc('fecha')
            ->orderBy('nombre')
            ->orderBy('apellido');

        $headings = ['Personal ID', 'Personal', 'CI', 'Sucursal', 'Fecha', 'Estado'];

        return $this->buildResponse(
            title: 'Reporte de Inasistencias',
            filenamePrefix: 'reporte-inasistencias',
            filters: $filters,
            headings: $headings,
            query: $query,
            transformer: fn ($row) => [
                'personal_id' => $row->personal_id,
                'personal' => trim($row->nombre . ' ' . $row->apellido),
                'ci' => $row->ci,
                'sucursal' => $row->sucursal,
                'fecha' => $this->formatDate($row->fecha),
                'estado' => 'inasistencia',
            ]
        );
    }

    private function buildResponse(
        string $title,
        string $filenamePrefix,
        array $filters,
        array $headings,
        Builder|QueryBuilder $query,
        callable $transformer
    ): array {
        if ($filters['export']) {
            $rows = $query->get()->map($transformer)->values();

            return [
                'title' => $title,
                'filename' => $filenamePrefix . '-' . now()->format('YmdHis'),
                'headings' => $headings,
                'rows' => $rows,
                'filters' => $filters,
                'data' => $rows,
            ];
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->paginate(
            perPage: $filters['per_page'],
            columns: ['*'],
            pageName: 'page',
            page: $filters['page']
        );

        $paginator->through($transformer);

        return [
            'title' => $title,
            'filename' => $filenamePrefix . '-' . now()->format('YmdHis'),
            'headings' => $headings,
            'rows' => collect(),
            'filters' => $filters,
            'data' => $paginator,
        ];
    }

    private function baseAsistenciaQuery(array $filters): Builder
    {
        return Asistencia::query()
            ->select([
                'asistencia.id',
                'asistencia.personal_id',
                'asistencia.fecha',
                'asistencia.hora_entrada',
                'asistencia.hora_salida',
                'asistencia.estado',
                'personal.ci',
                'personal.nombre',
                'personal.apellido',
                DB::raw("TRIM(personal.nombre || ' ' || personal.apellido) as personal"),
                DB::raw("COALESCE(sucursal.nombre, '') as sucursal"),
            ])
            ->join('personal', 'personal.id', '=', 'asistencia.personal_id')
            ->leftJoin('sucursal', 'sucursal.id', '=', 'personal.sucursal_id')
            ->where('personal.estado', 1)
            ->whereBetween('asistencia.fecha', [$filters['fecha_inicio'], $filters['fecha_fin']]);
    }

    private function baseAsistenciaConMinutosTrabajadosQuery(array $filters): Builder
    {
        return $this->baseAsistenciaQuery($filters)->selectRaw(
            'ROUND(EXTRACT(EPOCH FROM (asistencia.hora_salida - asistencia.hora_entrada)) / 60) as minutos_trabajados'
        );
    }

    private function baseInasistenciasQuery(array $filters): QueryBuilder
    {
        $fechaInicio = Carbon::parse($filters['fecha_inicio'])->toDateString();
        $fechaFin = Carbon::parse($filters['fecha_fin'])->toDateString();

        return DB::table('personal')
            ->select([
                'personal.id as personal_id',
                'personal.nombre',
                'personal.apellido',
                'personal.ci',
                'fechas.fecha',
                DB::raw("COALESCE(sucursal.nombre, '') as sucursal"),
            ])
            ->join(
                DB::raw("generate_series('{$fechaInicio}'::date, '{$fechaFin}'::date, interval '1 day') as fechas(fecha)"),
                DB::raw('true'),
                '=',
                DB::raw('true')
            )
            ->leftJoin('sucursal', 'sucursal.id', '=', 'personal.sucursal_id')
            ->leftJoin('asistencia', function ($join) {
                $join->on('asistencia.personal_id', '=', 'personal.id')
                    ->on('asistencia.fecha', '=', 'fechas.fecha');
            })
            ->where('personal.estado', 1)
            ->whereNull('asistencia.id');
    }

    private function normalizeFilters(array $filters): array
    {
        $fechaInicio = Carbon::parse($filters['fecha_inicio'] ?? today())->toDateString();
        $fechaFin = Carbon::parse($filters['fecha_fin'] ?? today())->toDateString();

        return [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'page' => (int) ($filters['page'] ?? 1),
            'per_page' => (int) ($filters['per_page'] ?? 15),
            'export' => $filters['export'] ?? null,
        ];
    }

    private function formatDate(mixed $value): ?string
    {
        if (!$value) {
            return null;
        }

        return Carbon::parse($value)->format('Y-m-d');
    }

    private function formatDateTime(mixed $value): ?string
    {
        if (!$value) {
            return null;
        }

        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    private function formatMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $remainingMinutes);
    }
}
