<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bienvenid@ - <span class="text-green-600">{{ auth()->user()->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <section class="px-8 py-6 flex justify-center mx-52">
                    <div class="bg-white shadow-md w-full max-w-3xl rounded-lg">
                        
                        <div tabindex="0" class="collapse collapse-arrow bg-base-200 rounded-lg ">
                            <input type="checkbox" />
                            <div class="collapse-title text-lg font-medium">
                                Sobre la plataforma
                            </div>
                            <div class="collapse-content bg-white">
                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                    <li>Aquí puedes registrar tu peso y estatura <b>semanalmente</b>.</li>
                                    <li>El sistema calcula tu IMC despues que ingreses los valores.</li>
                                    <li>Solo se permite un registro por semana.</li>
                                </ul>
                            </div>
                        </div>
                    
                        <div tabindex="0" class="collapse collapse-arrow bg-base-200 rounded-lg ">
                            <input type="checkbox" />
                            <div class="collapse-title text-lg font-medium">
                                Funcionalidad de la calculadora
                            </div>
                            <div class="collapse-content bg-white">
                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                    <li>En el navegador ubicado en la parte superior de la venta hay una opcion llamada <b>"Calculadora"</b>.</li>
                                    <li>Al hacer clic ahí se abrira la calculadora para usuarios registrados.</li>
                                    <li>Una vez llenado los datos deberás precionar el boton <b>"Calcular IMC"</b>.</li>
                                    <li>El sistema calculará y guardara tu IMC y te dirá en que rango te encuentras.</li>
                                    <li>Si ya habias usado la calculadora en la semana esta no registrara los datos y arrojara un mensaja informando que no se hará mas de 1 registro por semana.</li>
                                </ul>
                            </div>
                        </div>
                    
                        <div tabindex="0" class="collapse collapse-arrow bg-base-200 rounded-lg">
                            <input type="checkbox" />
                            <div class="collapse-title text-lg font-medium">
                                "Mi Progreso"
                            </div>
                            <div class="collapse-content bg-white">
                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                    <li>En el mismo navegador se encuentra otra opcion llamada <b>"Mi Progreso"</b>.</li>
                                    <li>En esa sección, puedes ver tu historial y gráfico de evolución.</li>
                                    <li>También puedes ver tu promedio de IMC y en qué rango te encuentras.</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </div>
</x-app-layout>
