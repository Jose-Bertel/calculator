<?php

namespace App\Livewire\Administracion;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\UserService;

class Usuarios extends Component
{
    use WithPagination;

    public string $search = '';
    public array $selectedRoles = [];

    public $name;
    public $email;
    public $password;
    public $birthdate;
    public $role;
    public $userId;
    public $perPage = 10;

    public bool $mostrarModalRol = false;
    public ?int $usuarioIdSeleccionado = null;
    public string $rolSeleccionado = '';

    public bool $mostrarModalEliminar = false;
    public $usuarioAEliminar = null;

    public bool $modalFormulario = false;

    public bool $modalCambiarNombre = false;
    public string $newName = '';

    public bool $modalCambioEmail = false;
    public string $newEmail = '';

    public bool $modalCambioPassword = false;
    public string $newPassword = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedRoles()
    {
        $this->resetPage();
    }

    public function render()
    {
        $usuarios = UserService::buscar($this->search, $this->perPage, $this->selectedRoles);

        return view('livewire.administracion.usuarios', [
            'usuarios' => $usuarios
        ]);
    }

    // ---------- FUNCIONES PARA ROLES ----------

    public function abrirModalAsignarRol(int $usuarioId): void
    {
        $this->usuarioIdSeleccionado = $usuarioId;
        $this->rolSeleccionado = '';
        $this->mostrarModalRol = true;
    }

    public function asignarRol(): void
    {
        if (!$this->rolSeleccionado) {
            $this->addError('rolSeleccionado', 'Debe seleccionar un rol.');
            return;
        }

        $user = UserService::getById($this->usuarioIdSeleccionado);
        if ($user) {
            UserService::assignRole($user, $this->rolSeleccionado);
        }

        $this->reset(['mostrarModalRol', 'usuarioIdSeleccionado', 'rolSeleccionado']);
        $this->resetPage();
    }

    public function quitarRol(int $userId, string $role): void
    {
        $user = UserService::getById($userId);
        if ($user) {
            UserService::removeRole($user, $role);
            $this->resetPage();
        }
    }

    public function cerrarMostrarModalRol(): void
    {
        $this->mostrarModalRol = false;
    }

    // ---------- FUNCIONES PARA ELIMINAR ----------

    public function confirmarEliminacion(int $userId): void
    {
        $this->usuarioAEliminar = UserService::getById($userId);
        $this->mostrarModalEliminar = true;
    }

    public function eliminarUsuario(): void
    {
        if ($this->usuarioAEliminar) {
            UserService::delete($this->usuarioAEliminar);
        }
        $this->usuarioAEliminar = null;
        $this->mostrarModalEliminar = false;
        $this->resetPage();
    }

    public function cerrarMostrarModalEliminar(): void
    {
        $this->usuarioAEliminar = null;
        $this->mostrarModalEliminar = false;
    }

    // ---------- FUNCIONES PARA FORMULARIO AGREGAR USUARIO ----------

    public function abrirModalFormulario(): void
    {
        $this->resetForm();
        $this->userId = null;
        $this->modalFormulario = true;
    }

    public function cerrarModalFormulario(): void
    {
        $this->modalFormulario = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'id' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];

        $user = UserService::upSert($data);
        UserService::assignRole($user, $this->role);

        $this->modalFormulario = false;
        $this->resetForm();
        $this->resetPage();
    }

    protected function resetForm(): void
    {
        $this->reset(['name', 'email', 'password', 'role', 'userId']);
        $this->resetValidation();
    }

    // ---------- FUNCIONES PARA CAMBIO DE NOMBRE ----------

    public function abrirModalCambioNombre(int $userId): void
    {
        $user = UserService::getById($userId);
        if ($user) {
            $this->userId = $userId;
            $this->newName = $user->name;
            $this->modalCambiarNombre = true;
        }
    }

    public function guardarNuevoNombre(): void
    {
        $this->validate([
            'newName' => 'required|string|min:3|max:255',
        ]);

        UserService::actualizarNombre($this->userId, $this->newName);
        $this->cerrarModalCambioNombre();
    }

    public function cerrarModalCambioNombre(): void
    {
        $this->reset(['userId', 'newName']);
        $this->modalCambiarNombre = false;
    }

    // ---------- FUNCIONES PARA CAMBIO DE EMAIL ----------

    public function abrirModalCambioEmail(int $userId): void
    {
        $user = UserService::getById($userId);
        if ($user) {
            $this->userId = $userId;
            $this->newEmail = $user->email;
            $this->modalCambioEmail = true;
        }
    }

    public function guardarNuevoEmail(): void
    {
        $this->validate([
            'newEmail' => 'required|email|min:3|max:255',
        ]);

        UserService::actualizarEmail($this->userId, $this->newEmail);
        $this->cerrarModalCambioEmail();
    }

    public function cerrarModalCambioEmail(): void
    {
        $this->reset(['userId', 'newEmail']);
        $this->modalCambioEmail = false;
    }

    // ---------- FUNCIONES PARA CAMBIO DE CONTRASEÑA ----------

    public function abrirModalCambioPassword(int $userId): void
    {
        $this->userId = $userId;
        $this->newPassword = '';
        $this->modalCambioPassword = true;
    }

    public function guardarNuevoPassword(): void
    {
        $this->validate([
            'newPassword' => 'required|string|min:6|max:255',
        ]);

        UserService::actualizarPassword($this->userId, $this->newPassword);
        $this->cerrarModalCambioPassword();
    }

    public function cerrarModalCambioPassword(): void
    {
        $this->reset(['userId', 'newPassword']);
        $this->modalCambioPassword = false;
    }
}
