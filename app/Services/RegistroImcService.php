<?php

namespace App\Services;

use App\Models\RegistroImc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RegistroImcService
{

public static function crearRegistro(int $userId, float $peso, float $estatura, float $imc): ?RegistroImc
{
    // Calcular inicio y fin de la semana actual (domingo a sábado)
    $inicioSemana = Carbon::now()->startOfWeek(Carbon::SUNDAY);
    $finSemana = Carbon::now()->endOfWeek(Carbon::SATURDAY);

    // Verificar si ya existe un registro del usuario esta semana
    $yaRegistrado = RegistroImc::where('user_id', $userId)
        ->whereBetween('created_at', [$inicioSemana, $finSemana])
        ->exists();

    if ($yaRegistrado) {
        // No crear el registro si ya existe uno esta semana
        return null;
    }

    // Crear nuevo registro
    return RegistroImc::create([
        'user_id'   => $userId,
        'peso'      => $peso,
        'estatura'  => $estatura,
        'imc'       => $imc,
    ]);
}

    // Eliminar un registro por su ID
    public static function eliminarRegistro(int $registroId): bool
    {
        $registro = RegistroImc::find($registroId);
        if ($registro) {
            return $registro->delete();
        }
        return false;
    }

    // Listar registros por user_id, ordenados por fecha descendente
    public static function listarPorUsuario(int $userId)
    {
        return RegistroImc::where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();
    }
    public static function listarPorUsuarioPaginado(int $userId, int $porPagina = 7)
    {
        return RegistroImc::where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->paginate($porPagina);
    }
    public static function promedioImcPorUsuario(int $userId): ?float
    {
        return RegistroImc::where('user_id', $userId)
            ->avg('imc');
    }

    public static function obtenerRangoIMC(float $imc): string
    {
        switch (true) {
            case $imc < 18.5:
                return 'Bajo peso';
            case $imc >= 18.5 && $imc < 25:
                return 'Normal';
            case $imc >= 25 && $imc < 30:
                return 'Sobrepeso';
            case $imc >= 30:
                return 'Obesidad';
            default:
                return 'No definido';
        }
    }
    public static function conteoPorRangoImc(int $userId): array
    {
        $registros = RegistroImc::where('user_id', $userId)->get();

        $conteo = [
            'Bajo peso' => 0,
            'Normal' => 0,
            'Sobrepeso' => 0,
            'Obesidad' => 0,
        ];

        foreach ($registros as $registro) {
            $imc = $registro->imc;

            if ($imc < 18.5) {
                $conteo['Bajo peso']++;
            } elseif ($imc >= 18.5 && $imc < 25) {
                $conteo['Normal']++;
            } elseif ($imc >= 25 && $imc < 30) {
                $conteo['Sobrepeso']++;
            } else {
                $conteo['Obesidad']++;
            }
        }

        return $conteo;
    }

public static function listarConEdad()
{
    // Subconsulta para obtener el último registro por usuario
    $subquery = DB::table('registro_imcs')
        ->select('user_id', DB::raw('MAX(created_at) as ultimo_registro'))
        ->groupBy('user_id');

    // Join con la subconsulta para obtener sólo el último registro por usuario
    return DB::table('registro_imcs')
        ->joinSub($subquery, 'ultimos', function ($join) {
            $join->on('registro_imcs.user_id', '=', 'ultimos.user_id')
                 ->on('registro_imcs.created_at', '=', 'ultimos.ultimo_registro');
        })
        ->join('users', 'registro_imcs.user_id', '=', 'users.id')
        ->select(
            'registro_imcs.user_id',
            'registro_imcs.imc',
            DB::raw('TIMESTAMPDIFF(YEAR, users.birthdate, registro_imcs.created_at) as edad')
        )
        ->orderBy('edad', 'asc')
        ->get();
}

public static function promedioGeneralImc()
{
    // Tomar el promedio solo de los últimos registros por usuario (no todos)
    $subquery = DB::table('registro_imcs')
        ->select('user_id', DB::raw('MAX(created_at) as ultimo_registro'))
        ->groupBy('user_id');

    return DB::table('registro_imcs')
        ->joinSub($subquery, 'ultimos', function ($join) {
            $join->on('registro_imcs.user_id', '=', 'ultimos.user_id')
                 ->on('registro_imcs.created_at', '=', 'ultimos.ultimo_registro');
        })
        ->avg('registro_imcs.imc');
}
public static function promedioImcPorRangoEdad()
{
    return DB::table('registro_imcs')
        ->selectRaw("
            CASE
                WHEN edad BETWEEN 0 AND 10 THEN '0-10'
                WHEN edad BETWEEN 11 AND 18 THEN '11-18'
                WHEN edad BETWEEN 19 AND 30 THEN '19-25'
                WHEN edad BETWEEN 19 AND 30 THEN '26-35'
                WHEN edad BETWEEN 31 AND 50 THEN '36-48'
                WHEN edad BETWEEN 51 AND 65 THEN '49-65'
                ELSE '66+'
            END AS rango_edad,
            AVG(imc) AS promedio_imc
        ")
        ->fromSub(function ($query) {
            $query->from('registro_imcs')
                ->join('users', 'registro_imcs.user_id', '=', 'users.id')
                ->selectRaw('registro_imcs.imc, TIMESTAMPDIFF(YEAR, users.birthdate, registro_imcs.created_at) as edad');
        }, 'subquery')
        ->groupBy('rango_edad')
        ->orderByRaw("FIELD(rango_edad, '0-10', '11-18', '19-25', '26-35', '36-48','49-65', '66+')")
        ->get();
}


}
