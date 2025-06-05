<div class="max-w-4xl my-2 mx-auto p-6 bg-white shadow-xl rounded-xl">
    <h2 class="text-2xl font-bold mb-6 text-center text-green-700">Mi Progreso de IMC<br>(Por Semana)</h2>

    @php
        use Carbon\Carbon;

        // Para la gráfica (orden ascendente por fecha)
        $registrosOrdenadosAsc = collect($registros2)->sortBy('created_at')->values()->toArray();

        $labels = array_map(fn($r) => Carbon::parse($r['created_at'])->format('d/m/Y'), $registrosOrdenadosAsc);
        $datos = array_map(fn($r) => $r['imc'], $registrosOrdenadosAsc);

    @endphp

    <canvas id="imcChart" class="mb-8 w-full h-64 bg-gray-100 rounded"></canvas>
    <table class="table table-zebra w-full">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Peso (kg)</th>
            <th>Estatura (m)</th>
            <th>IMC</th>
            <th>Rango</th> {{-- Nueva columna --}}
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('imcChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const labels = @json($labels);
        const data = @json($datos);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'IMC',
                    data: data,
                    borderColor: 'rgb(0, 155, 0)',
                    backgroundColor: 'rgb(151, 255, 128,0.2)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#3b82f6' // azul tailwind
                        }
                    },
                    title: {
                        display: true,
                        text: 'Progreso de Índice de Masa Corporal (IMC)',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'nearest',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `IMC: ${context.parsed.y.toFixed(2)}`;
                            },
                            title: function(context) {
                                return `Fecha: ${context[0].label}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Fecha del registro',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        beginAtZero: false,
                        suggestedMin: 10,
                        suggestedMax: 40,
                        title: {
                            display: true,
                            text: 'IMC',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    });
</script>
