<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\RegistroImcService;

class DatosGenerales extends Component
{
    public $datosXY = [];
    public $promedioGeneral;
    public $promediosPorRangoEdad;

    public function mount()
    {
        $registrosConEdad = RegistroImcService::listarConEdad();

        // Construimos array para Chart.js: { x: imc, y: edad }
        $this->datosXY = $registrosConEdad->map(function($item) {
            return [
                'x' => $item->imc,
                'y' => $item->edad,
            ];
        })->toArray();

        // Promedio general de IMC
        $this->promedioGeneral = RegistroImcService::promedioGeneralImc();
        $this->promediosPorRangoEdad = RegistroImcService::promedioImcPorRangoEdad();
    }

    public function render()
    {
        return view('livewire.datos-generales', [
            'datosXY' => $this->datosXY,
            'promedioGeneral' => $this->promedioGeneral,
        ]);
    }
}
