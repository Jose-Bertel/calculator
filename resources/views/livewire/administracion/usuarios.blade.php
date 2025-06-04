<div class="mx-16 my-5 px-3 py-2 space-y-4 bg-white rounded-xl">

    {{-- Título --}}
    <h2 class="text-2xl font-bold">Gestión de Usuarios</h2>

    {{-- Controles: búsqueda y cantidad por página --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="form-control w-full sm:max-w-xs">
            <input
                wire:model.live="search"
                type="text"
                placeholder="Buscar por nombre o email"
                class="input input-bordered w-full"
            />
        </div>

        <div class="form-control w-full sm:w-auto">
            <select wire:model.live="perPage" class="select select-bordered">
                <option value="5">5 por página</option>
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
            </select>
        </div>
    </div>

  {{-- FILTROS POR ROL --}}
  <div class="mb-6 flex gap-4 items-center">
    <label class="flex items-center gap-2">
      <input type="checkbox" wire:model.live="selectedRoles" value="ADMINISTRADOR" class="checkbox checkbox-primary" />
      Administradores
    </label>
    <label class="flex items-center gap-2">
      <input type="checkbox" wire:model.live="selectedRoles" value="USUARIO" class="checkbox checkbox-primary" />
      Usuarios
    </label>
  </div>

  {{-- TABLA DE USUARIOS --}}
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="table w-full">
            <thead>
                <tr class="bg-base-200">
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Roles</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($usuarios as $usuario)
        <tr>
          <td>{{ $usuario->id }}</td>
          <td>
            {{ $usuario->name }}
            <button class="bg-blue-300 rounded-md" title="Quitar rol"wire:click="abrirModalCambioNombre({{ $usuario->id }})">✏️</button>
          </td>
<td 
    x-data="{ 
        resaltado: false, 
        copiarEmail(email) { 
            navigator.clipboard.writeText(email); 
            this.resaltado = true; 
            setTimeout(() => this.resaltado = false, 1000); 
        } 
    }"
    class="rounded"
>
    <label 
        :class="{ 'bg-blue-200': resaltado }"
        class="transition-colors duration-1000 ease-in-out select-text cursor-text p-1 rounded"
    >
        {{ $usuario->email }}
    </label>

    <button class="bg-blue-300 rounded-md ml-1" title="Editar correo"
            wire:click="abrirModalCambioEmail({{ $usuario->id }})">✏️</button>
    
    <button class="bg-green-300 rounded-md ml-1" title="Copiar correo"
            @click="copiarEmail('{{ $usuario->email }}')">📋</button>
</td>

          {{-- <td>
            {{ $usuario->email }}
            <button class="bg-blue-300 rounded-md" title="Quitar rol"wire:click="abrirModalCambioEmail({{ $usuario->id }})">✏️</button>
              <button class="bg-green-300 rounded-md ml-1" title="Copiar correo" @click="copiarEmail('{{ $usuario->email }}')">📋</button>
          </td> --}}
          <td>
            <div class="flex flex-col gap-2 justify-center align-middle">
              
                            @if($usuario->getRoleNames()->count() >= 1)
                            @foreach($usuario->getRoleNames() as $role)
                            <span 
                                class="relative group badge px-5 py-2 mr-1 
                                    @php
                                        switch ($role) {
                                            case 'ADMINISTRADOR': echo 'badge-primary'; break;
                                            case 'USUARIO': echo 'badge-success'; break;
                                            default: echo 'badge-info'; break;
                                        }
                                    @endphp"
                            >
                            {{$role}}
                            @if($usuario->getRoleNames()->count() > 1)
                                            <button 
                                                class="absolute top-0 right-0 -mt-1 -mr-1 text-white bg-red-600 rounded-full w-4 h-4 text-xs hidden group-hover:flex items-center justify-center"
                                                title="Quitar rol"
                                                wire:click="quitarRol('{{ $usuario->id }}', '{{ $role }}')"
                                            >
                                                ×
                                            </button>
                                        @endif
                            </span>
                        @endforeach
                        @else
                                <span 
                                class="relative group badge px-5 py-2 mr-1 badge-ghost">
                                        Sin rol asignado
                                </span>
                            @endif
                        </div>
          </td>
          <td>
            <details close>
                        <summary class="menu btn btn-ghost btn-xs">...</summary>
                        <ul class="flex flex-col justify-center align-middle">
                            <li class="my-1">
                                <button wire:click="confirmarEliminacion({{ $usuario->id }})" class="btn btn-xs btn-error">Eliminar</button>
                            </li>
                            <li class="my-1">
                                <button wire:click="abrirModalAsignarRol({{ $usuario->id }})" class="btn btn-xs btn-info">Asignar rol</button>
                            </li>
                        </ul>
                        </details>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $usuarios->links() }}
    </div>

  {{-- MODAL ASIGNAR ROL --}}
  <input type="checkbox" id="modal-rol" class="modal-toggle" wire:model="mostrarModalRol" />
  <div class="modal">
    <div class="modal-box">
      <h3 class="font-bold text-lg mb-4">Asignar Rol</h3>
      <select wire:model="rolSeleccionado" class="select select-bordered w-full">
        <option value="">Seleccione un rol</option>
        <option value="ADMINISTRADOR">ADMINISTRADOR</option>
        <option value="USUARIO">USUARIO</option>
      </select>
      @error('rolSeleccionado') 
        <p class="text-error text-sm mt-1">{{ $message }}</p>
      @enderror

      <div class="modal-action">
        <button wire:click="asignarRol" class="btn btn-primary">Asignar</button>
        <button wire:click="cerrarMostrarModalRol" class="btn btn-secondary">Cancelar</button>
      </div>
    </div>
  </div>

  {{-- MODAL CONFIRMAR ELIMINACIÓN --}}
  <input type="checkbox" id="modal-eliminar" class="modal-toggle" wire:model="mostrarModalEliminar" />
  <div class="modal">
    <div class="modal-box">
      <h3 class="font-bold text-lg mb-4 text-red-600">Confirmar Eliminación</h3>
      <p class="mb-4">¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.</p>

      <div class="modal-action">
        <button wire:click="eliminarUsuario" class="btn btn-error">Eliminar</button>
        <button wire:click="cerrarMostrarModalEliminar" class="btn btn-active">Cancelar</button>
      </div>
    </div>
  </div>

  {{-- MODAL AGREGAR USUARIO --}}
  <input type="checkbox" id="modal-formulario" class="modal-toggle" wire:model="modalFormulario" />
  <div class="modal">
    <div class="modal-box max-w-md">
      <h3 class="font-bold text-lg mb-4">Agregar Usuario</h3>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Nombre</label>
        <input type="text" wire:model.defer="name" class="input input-bordered w-full" />
        @error('name') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Correo</label>
        <input type="email" wire:model.defer="email" class="input input-bordered w-full" />
        @error('email') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Contraseña</label>
        <input type="password" wire:model.defer="password" class="input input-bordered w-full" />
        @error('password') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-semibold">Rol</label>
        <select wire:model.defer="role" class="select select-bordered w-full">
          <option value="">Seleccione un rol</option>
          <option value="ADMINISTRADOR">ADMINISTRADOR</option>
          <option value="DOCENTE">DOCENTE</option>
          <option value="ESTUDIANTE">ESTUDIANTE</option>
        </select>
        @error('role') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="modal-action">
        <button wire:click="save" class="btn btn-primary">Guardar</button>
        <button wire:click="cerrarModalFormulario" class="btn btn-secondary">Cancelar</button>
      </div>
    </div>
  </div>

  {{-- MODAL CAMBIAR NOMBRE --}}
  <input type="checkbox" id="modal-cambio-nombre" class="modal-toggle" wire:model="modalCambiarNombre" />
  <div class="modal">
    <div class="modal-box max-w-md">
      <h3 class="font-bold text-lg mb-4">Cambiar Nombre</h3>

      <input type="text" wire:model.defer="newName" class="input input-bordered w-full mb-4" />
      @error('newName') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror

      <div class="modal-action">
        <button wire:click="guardarNuevoNombre" class="btn btn-primary">Guardar</button>
        <button wire:click="cerrarModalCambioNombre" class="btn btn-secondary">Cancelar</button>
      </div>
    </div>
  </div>

  {{-- MODAL CAMBIAR EMAIL --}}
  <input type="checkbox" id="modal-cambio-email" class="modal-toggle" wire:model="modalCambioEmail" />
  <div class="modal">
    <div class="modal-box max-w-md">
      <h3 class="font-bold text-lg mb-4">Cambiar Email</h3>

      <input type="email" wire:model.defer="newEmail" class="input input-bordered w-full mb-4" />
      @error('newEmail') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror

      <div class="modal-action">
        <button wire:click="guardarNuevoEmail" class="btn btn-primary">Guardar</button>
        <button wire:click="cerrarModalCambioEmail" class="btn btn-secondary">Cancelar</button>
      </div>
    </div>
  </div>

</div>
