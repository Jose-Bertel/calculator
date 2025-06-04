<?php

namespace App\Services;

use App\Models\RegistroImc;
use Carbon\Carbon;

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

}
