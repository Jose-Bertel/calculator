<div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-xl rounded-xl flex flex-col align-middle text-center justify-center">
    <h2 class="text-2xl font-bold mb-6 text-center text-green-700">Calculadora de IMC</h2>

    @if ($mensaje)
        <div class="alert bg-{{$color}} shadow-sm mb-4 ">
            {{ $mensaje }}
        </div>
    @endif

    <div class="form-control mb-4">
        <label class="label">
            <span class="label-text font-medium">Peso (kg)
        </label>
        <br>
        <input type="number" wire:model="peso" step="0.1" class="input input-bordered" placeholder="Ej: 70" />
    </div>

    <div class="form-control mb-4">
        <label class="label">
            <span class="label-text font-medium">Estatura (m)</span>
        </label>
        <br>
        <input type="number" wire:model="estatura" step="0.01" class="input input-bordered" placeholder="Ej: 1.75" />
    </div>

    <div class="form-control mb-6">
        <label class="label">
            <span class="label-text font-medium">IMC calculado</span>
        </label>
        <br>
    @if ($imcregistro)
        <label class="label">
            <span class="label-text font-medium">{{$imc}}</span>
        </label>
        <p class="text-sm font-semibold mt-1 text-gray-700">Rango: <span class="font-bold">{{ $rangoIMC }}</span></p>
    @endif
    </div>

    <button wire:click="guardar()" class="btn bg-green-700 text-white w-full">
        Calcular IMC
    </button>
</div>
