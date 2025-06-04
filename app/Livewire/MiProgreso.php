<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistroImcService;
use Livewire\WithPagination;

class MiProgreso extends Component
{
    use WithPagination;
    
    public $registros = [];
    public $promedioIMC;
    public $conteoRangos;

    public function mount()
    {
        $userId = Auth::id();

        $this->registros = RegistroImcService::listarPorUsuario($userId)->sortBy('created_at')->values()->toArray();
        $this->promedioIMC = RegistroImcService::promedioImcPorUsuario($userId);
        $this->conteoRangos = RegistroImcService::conteoPorRangoImc($userId);
    }

    public function render()
    {
        return view('livewire.mi-progreso');
    }
}
