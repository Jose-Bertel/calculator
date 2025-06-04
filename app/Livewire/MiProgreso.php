<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistroImcService;
use Livewire\WithPagination;

class MiProgreso extends Component
{
    use WithPagination;
    
    public $porPagina = 7; // Puedes cambiar este número
    public $promedioIMC;
    public $conteoRangos;
    public $registros2 = [];

    public function mount()
    {
        $userId = Auth::id();

        $this->registros2 = RegistroImcService::listarPorUsuario($userId)->sortBy('created_at')->values()->toArray();
        $this->promedioIMC = RegistroImcService::promedioImcPorUsuario($userId);
        $this->conteoRangos = RegistroImcService::conteoPorRangoImc($userId);
    }
    public function render()
    {
        $userId = Auth::id();

        return view('livewire.mi-progreso', [
            'registros' => RegistroImcService::listarPorUsuarioPaginado($userId, $this->porPagina),
        ]);
    }
}
