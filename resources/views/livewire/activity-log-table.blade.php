<div class="overflow-x-auto px-16 py-12">
    <table class="table w-full table-zebra border">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th>Fecha</th>
                <th>Modelo</th>
                <th>Usuario</th>
                <th>Descripción</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td class="w-32 ">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td class="w-32 ">{{ class_basename($log->subject_type) }}</td>
                    <td class="w-32 ">{{ optional($log->causer)->name ?? '-' }}</td>
                    <td class="w-32 ">{{ $log->description }}</td>
                    <td>
                        <div class="text-sm">
                            @if ($log->event === 'updated')
                                @if (isset($log->properties['old']))
                                    <div><strong>Antes:</strong></div>
                                    <pre class="bg-blue-200 p-2 rounded mb-2 overflow-x-auto">
                    {{ json_encode($log->properties['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                                @if (isset($log->properties['attributes']))
                                    <div><strong>Después:</strong></div>
                                    <pre class="bg-blue-200 p-2 rounded overflow-x-auto">
                    {{ json_encode($log->properties['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif

                            @elseif ($log->event === 'created')
                                @if (isset($log->properties['attributes']))
                                    <div><strong>Datos creados:</strong></div>
                                    <pre class="bg-blue-200 p-2 rounded overflow-x-auto">
                    {{ json_encode($log->properties['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif

                            @elseif ($log->event === 'deleted')
                                @if (isset($log->properties['old']))
                                    <div><strong>Registro eliminado:</strong></div>
                                    <pre class="bg-blue-200 p-2 rounded overflow-x-auto">
                    {{ json_encode($log->properties['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @else
                                    <span class="text-gray-500">Sin datos disponibles</span>
                                @endif

                            @else
                                <span class="text-gray-500">Sin detalles</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
