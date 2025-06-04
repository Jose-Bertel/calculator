<?php

namespace App\Livewire\Administracion;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistroImcService;
use App\Services\UserService;
use Livewire\WithPagination;

class ProgresoUsuario extends Component
{
    use WithPagination;
    
    public $porPagina = 7; // Puedes cambiar este número
    public $promedioIMC;
    public $conteoRangos;
    public $registros2 = [];
    public $search;
    public $userId = 0;
    public $user;
    public $registroId;

    public bool $modalActualizarDatos = false;
    public $newPeso;
    public $newAltura;
    public $newImc;
    public bool $mostrarModalEliminar = false;
    public $registroAEliminar = null;

    public function mount()
    {
        $this->user = UserService::getById($this->userId);
        $this->promedioIMC = RegistroImcService::promedioImcPorUsuario($this->userId);
        $this->conteoRangos = RegistroImcService::conteoPorRangoImc($this->userId);
    }
    public function render()
    {
        return view('livewire.administracion.progreso-usuario', [
            'registros' => RegistroImcService::listarPorUsuarioPaginado($this->userId, $this->porPagina),
        ]);
    }
    public function buscar(){
        $usuario = UserService::obtenerIdPorEmail($this->search);
        if ($usuario) {
            $this->userId = $usuario->id;
            $this->mount();
        }
    }
    public function abrirModalEliminar(int $userId): void
    {
        $this->registroAEliminar = RegistroImcService::getById($userId);
        $this->mostrarModalEliminar = true;
    }
    public function confirmarEliminacion(): void
    {
        if ($this->registroAEliminar) {
            RegistroImcService::delete($this->registroAEliminar);
        }
        $this->registroAEliminar = null;
        $this->mostrarModalEliminar = false;
        $this->mount();
    }
    public function cerrarMostrarModalEliminar(){
        $this->registroAEliminar = null;
        $this->mostrarModalEliminar = false;
    }
    public function abrirModalActualizar($id)
    {
        $registro = RegistroImcService::getById($id);
        $this->registroId = $registro->id; 
        $this->newAltura = $registro->estatura;
        $this->newPeso = $registro->peso;
        $this->modalActualizarDatos = true;
    }
    public function actualizar(): void
    {
        $this->validate([
            'newPeso' => 'required|numeric|min:1',
            'newAltura' => 'required|numeric|min:0.1',
        ]);
        $this->newImc = round($this->newPeso / (($this->newAltura/100) ** 2),2);
        RegistroImcService::actualizarRegistroImc(
                    $this->registroId,
                    $this->newPeso,
                    $this->newAltura,
                    $this->newImc
        );
        $this->modalActualizarDatos = false;
        $this->mount();
    }
    public function cerrarModalActualizar(){
        $this->modalActualizarDatos = false;
    }
}
