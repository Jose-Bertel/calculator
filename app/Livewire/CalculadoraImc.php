<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistroImcService;

class CalculadoraImc extends Component
{
    public float $peso = 0;
    public float $estatura = 0;
    public float $imc = 0;
    public $imcregistro = false;
    public string $mensaje = '';
    public string $color = '';
    public $rangoIMC;

    public function guardar()
    {
        $this->validate([
            'peso' => 'required|numeric|min:1',
            'estatura' => 'required|numeric|min:0.1',
        ]);

        if ($this->imc === null) {
            $this->mensaje = 'Los datos son inválidos.';
            $this->reset(['peso', 'estatura', 'imc']);
            return;
        }else {
        $this->imc = round($this->peso / (($this->estatura/100) ** 2),2);
        $registro = RegistroImcService::crearRegistro(
            Auth::id(),
            $this->peso,
            $this->estatura,
            $this->imc
        );
            $this->rangoIMC = RegistroImcService::obtenerRangoIMC($this->imc);
        if ($registro) {
            $this->imcregistro = true;
            $this->color = 'success';
            $this->mensaje = 'IMC registrado correctamente.';
        } else {
            $this->imcregistro = true;
            $this->color = 'warning';
            $this->mensaje = 'El IMC no se ha registrado porque ya registraste uno esta semana.';
        }
        }
    }

    public function render()
    {
        return view('livewire.calculadora-imc');
    }
}
