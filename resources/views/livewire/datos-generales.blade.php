<div class="max-w-4xl mx-auto p-6 bg-white shadow-xl rounded-xl">
    <h2 class="text-2xl font-bold mb-6 text-center text-primary">Datos Generales de IMC</h2>

@if ($promediosPorRangoEdad)
<div class="max-w-4xl mx-auto mt-6 p-6 bg-white shadow rounded-lg">
    <h3 class="text-xl font-bold mb-4">Promedio de IMC por Rango de Edad</h3>

    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2 text-left">Rango de Edad</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Promedio IMC</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promediosPorRangoEdad as $item)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->rango_edad }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($item->promedio_imc, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif

    <canvas id="imcEdadChart" class="w-full h-64 bg-gray-100 rounded"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('imcEdadChart').getContext('2d');

        const dataPoints = @json($datosXY); // [{x: imc, y: edad}, ...]

        const chart = new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Edad vs IMC',
                    data: dataPoints,
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    zoom: {
                        pan: {
                            enabled: true,
                            mode: 'xy', // permite mover en ambas direcciones
                            modifierKey: null // mover con click directamente, sin teclas
                        },
                        zoom: {
                            wheel: {
                                enabled: true // zoom con scroll del mouse
                            },
                            pinch: {
                                enabled: true // zoom con gestos en touchpad o móvil
                            },
                            mode: 'xy' // zoom en ambos ejes
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold',
                            },
                            color: '#3b82f6',
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribución de IMC según Edad',
                        font: {
                            size: 18,
                            weight: 'bold',
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `IMC: ${ctx.parsed.x.toFixed(2)}, Edad: ${ctx.parsed.y} años`
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'IMC',
                            font: {
                                size: 14,
                                weight: 'bold',
                            }
                        },
                        beginAtZero: true,
                        suggestedMax: 50
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Edad (años)',
                            font: {
                                size: 14,
                                weight: 'bold',
                            }
                        },
                        beginAtZero: true,
                        suggestedMax: 100
                    }
                }
            }
        });
    });
</script>
