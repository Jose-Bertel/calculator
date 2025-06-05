<div class="max-w-4xl my-2 mx-auto p-6 bg-white shadow-xl rounded-xl">
            <div class="flex pb-3 form-control w-full">
            <input
                wire:model="search"
                type="email"
                placeholder="Buscar por email"
                class="input input-bordered w-full"
            />
            <button class="btn" wire:click="buscar()">Buscar</button>
        </div>
    @if ($user)
            <h1 class="font-bold text-center text-green-800 text-4xl">{{$user->name}}</h1>
            <h2 class="text-2xl font-bold mb-6 text-center text-green-700">Progreso de IMC</h2>
            <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Peso (kg)</th>
                    <th>Estatura (m)</th>
                    <th>IMC</th>
                    <th>Rango</th> {{-- Nueva columna --}}
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($registros as $registro)
                @php
                    $imc = $registro['imc'];
                    if ($imc < 18.5) {
                        $rango = 'Bajo peso';
                    } elseif ($imc >= 18.5 && $imc < 25) {
                        $rango = 'Normal';
                    } elseif ($imc >= 25 && $imc < 30) {
                        $rango = 'Sobrepeso';
                    } else {
                        $rango = 'Obesidad';
                    }
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($registro['created_at'])->format('d/m/Y H:i') }}</td>
                    <td>{{ $registro['peso'] }}</td>
                    <td>{{ $registro['estatura'] }}</td>
                    <td>{{ $imc }}</td>
                    <td>{{ $rango }}</td> {{-- Mostrar rango --}}
                    <td>
                        <details close>
                        <summary class="menu btn btn-ghost btn-xs">...</summary>
                        <ul class="flex flex-col justify-center align-middle">
                            <li class="my-1">
                                <button wire:click="abrirModalEliminar({{ $registro->id }})" class="btn btn-xs btn-error">Eliminar</button>
                            </li>
                            <li class="my-1">
                                <button wire:click="abrirModalActualizar({{ $registro->id }})" class="btn btn-xs btn-info">Actualizar</button>
                            </li>
                        </ul>
                        </details>
                    </td>
                </tr>
                @empty
                        <tr>
                            <td colspan="4">No se encontraron registros aun.</td>
                        </tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $registros->links() }}
        </div>


            @if ($promedioIMC !== null)
                <div class="max-w-4xl mx-auto mt-6 p-4 mb-6 bg-blue-100 rounded text-green-800 font-semibold">
                    Promedio de IMC: <span class="text-lg">{{ number_format($promedioIMC, 2) }}</span>
                </div>

                <div class="max-w-4xl mx-auto p-4 mb-6 bg-gray-100 rounded shadow">
                    <h3 class="text-xl font-bold mb-2">Cantidad de registros por rango</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        <li>Bajo peso: <strong>{{ $conteoRangos['Bajo peso'] ?? 0 }}</strong></li>
                        <li>Normal: <strong>{{ $conteoRangos['Normal'] ?? 0 }}</strong></li>
                        <li>Sobrepeso: <strong>{{ $conteoRangos['Sobrepeso'] ?? 0 }}</strong></li>
                        <li>Obesidad: <strong>{{ $conteoRangos['Obesidad'] ?? 0 }}</strong></li>
                    </ul>
                </div>
            @endif
    @endif
    {{-- MODAL CONFIRMAR ELIMINACIÓN --}}
    <input type="checkbox" id="modal-eliminar" class="modal-toggle" wire:model="mostrarModalEliminar" />
    <div class="modal">
        <div class="modal-box">
        <h3 class="font-bold text-lg mb-4 text-red-600">Confirmar Eliminación</h3>
        <p class="mb-4">¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.</p>

        <div class="modal-action">
            <button wire:click="confirmarEliminacion()" class="btn btn-error">Eliminar</button>
            <button wire:click="cerrarMostrarModalEliminar()" class="btn btn-active">Cancelar</button>
        </div>
        </div>
    </div>

    {{-- MODAL ACTUALIZAR DATOS --}}
  <input type="checkbox" id="modal-cambio-nombre" class="modal-toggle" wire:model="modalActualizarDatos" />
  <div class="modal">
    <div class="modal-box max-w-md">
      <h3 class="font-bold text-lg mb-4">Nuevo Peso</h3>
      <input type="text" wire:model.defer="newPeso" class="input input-bordered w-full mb-4" />
      @error('newPeso') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
      <h3 class="font-bold text-lg mb-4">Nueva Altura</h3>
      <input type="text" wire:model.defer="newAltura" class="input input-bordered w-full mb-4" />
      @error('newAltura') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
      <div class="modal-action">
        <button wire:click="actualizar()" class="btn btn-primary">Guardar</button>
        <button wire:click="cerrarModalActualizar" class="btn btn-secondary">Cancelar</button>
      </div>
    </div>
  </div>
</div>

