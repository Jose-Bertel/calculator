<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bienvenid@ - <span class="text-green-600">{{ auth()->user()->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-6xl text-center font-serif">Guia: Sobre la plataforma</h1>
                <section class="px-8 py-6 flex justify-center mx-52">
                    <div class="bg-white shadow-md w-full max-w-3xl rounded-lg">
                        
                        <div tabindex="0" class="collapse collapse-arrow bg-base-200 rounded-lg ">
                            <input type="checkbox" />
                            <div class="collapse-title text-lg font-medium">
                                Informacion basica de la plataforma
                            </div>
                            <div class="collapse-content bg-white">
                                <p>La plataforma de monitoreo de IMC es un sistema especializado diseñado para el registro y seguimiento del Índice de Masa Corporal de los usuarios. A continuación se detallan sus características fundamentales:</p>

                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                    <li><strong>Funcionalidad principal:</strong>
                                        <ul class="list-circle list-inside ml-5 space-y-1">
                                            <li>Registro semanal de peso y estatura con cálculo automático de IMC</li>
                                            <li>Clasificación según estándares OMS: Bajo peso, Normal, Sobrepeso y Obesidad</li>
                                        </ul>
                                    </li>
                                    
                                    <li><strong>Características técnicas:</strong>
                                        <ul class="list-circle list-inside ml-5 space-y-1">
                                            <li>Algoritmo de cálculo preciso según fórmula IMC = peso(kg)/(estatura(m))²</li>
                                            <li>Validación de rangos aceptables (peso: 20-300kg, estatura: 0.5-2.5m)</li>
                                            <li>Restricción de un registro semanal por usuario</li>
                                            <li>Base histórica protegida contra modificaciones no autorizadas</li>
                                        </ul>
                                    </li>

                                    <li><strong>Estructura de navegación:</strong>
                                        <ul class="list-circle list-inside ml-5 space-y-1">
                                            <li><strong>Calculadora:</strong> Interfaz principal para registro y cálculo</li>
                                            <li><strong>Mi Progreso:</strong> Visualización personalizada de historial</li>
                                            <li><strong>Datos Generales:</strong> Estadísticas generales sin informacion personal de usuarios</li>
                                        </ul>
                                    </li>
                                </ul>

                                <p>Políticas importantes:</p>
                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                    <li>Los datos personales están protegidos bajo normativa de privacidad</li>
                                    <li>Se recomienda consultar con profesionales de la salud para diagnósticos</li>
                                </ul>
                            </div>
                        </div>
                    
                        <div tabindex="1" class="collapse collapse-arrow bg-base-200 rounded-lg ">
                            <input type="checkbox" />
                            <div class="collapse-title text-lg font-medium">
                                "Calculadora"
                            </div>
                            <div class="collapse-content bg-white">
                                <p>La sección Calculadora permite a los usuarios calcular su Índice de Masa Corporal (IMC) ingresando su peso y estatura. Al hacer clic en el botón "Calcular", el sistema procesa los datos y devuelve:</p>
                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                <li>El valor numérico del IMC.</li>
                                La categoría correspondiente dentro del rango estándar (por ejemplo, "Normal", "Sobrepeso", etc.).</li>
                                </ul>
                                <p>Dado que esta funcionalidad forma parte de una plataforma con sesión iniciada, también se incorpora una característica de seguimiento semanal:</p>
                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                <li>Si el usuario no ha registrado aún su IMC en la semana actual, el sistema almacena automáticamente el resultado como parte del historial semanal y confirma que el registro fue exitoso.</li>
                                <li>En caso de que ya exista un registro previo en la misma semana, la calculadora seguirá mostrando el IMC y su categoría correspondiente, pero notificará al usuario que no se ha guardado un nuevo registro debido a que ya se había realizado uno en ese período.</li>
                                </ul>
                                <p>Esta funcionalidad está diseñada para fomentar el seguimiento regular del estado físico, permitiendo a los usuarios llevar un control de su progreso semana a semana.</p>
                            </div>
                        </div>
                    
                        <div tabindex="2" class="collapse collapse-arrow bg-base-200 rounded-lg">
                            <input type="checkbox" />
                            <div class="collapse-title text-lg font-medium">
                                "Mi Progreso"
                            </div>
                            <div class="collapse-content bg-white">
                                <p>La sección "Mi Progreso" ofrece una visión detallada de la evolución del Índice de Masa Corporal (IMC) a lo largo del tiempo. Al acceder a esta sección, los usuarios encontrarán:</p>
                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                    <li><strong>Gráfica de evolución:</strong> Muestra todos los registros semanales de IMC en formato gráfico, permitiendo visualizar tendencias y patrones de cambio.</li>
                                    <li><strong>Tabla de registros históricos:</strong> Presenta en detalle cada medición registrada, incluyendo fecha, peso, estatura y valor de IMC calculado.</li>
                                    <li><strong>Promedio general:</strong> Calcula automáticamente el valor promedio de todos los registros de IMC almacenados en el sistema.</li>
                                    <li><strong>Distribución por categorías:</strong> Muestra cuántas veces el usuario ha estado en cada rango de IMC (Bajo peso, Normal, Sobrepeso u Obesidad), proporcionando conteos para una mejor comprensión.</li>
                                </ul>
                            </div>
                        </div>
                    
                        <div tabindex="3" class="collapse collapse-arrow bg-base-200 rounded-lg">
                            <input type="checkbox" />
                            <div class="collapse-title text-lg font-medium">
                                "Datos Generales"
                            </div>
                            <div class="collapse-content bg-white">
                                <p>La sección "Datos Generales" proporciona un análisis estadístico del Índice de Masa Corporal (IMC) de todos los usuarios registrados en la plataforma. Esta herramienta está diseñada principalmente para administradores y ofrece:</p>
                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                    <li><strong>Tabla de promedios por rangos de edad:</strong>
                                        <ul class="list-circle list-inside ml-5 space-y-1">
                                            <li>Muestra el IMC promedio categorizado en 6 grupos etarios: 0-10 años, 11-18 años, 19-25 años, 36-48 años, 49-65 años y 66+ años</li>
                                            <li>Cada valor representa el cálculo agregado de todos los usuarios dentro de ese rango de edad</li>
                                            <li>Los datos se actualizan automáticamente con cada nuevo registro en el sistema</li>
                                        </ul>
                                    </li>
                                    
                                    <li><strong>Gráfico de dispersión IMC vs Edad:</strong>
                                        <ul class="list-circle list-inside ml-5 space-y-1">
                                            <li>Visualiza la relación entre la edad y los valores de IMC de todos los usuarios</li>
                                            <li>Cada punto representa un usuario registrado</li>
                                            <li>Incluye líneas de referencia para los valores saludables de IMC según la OMS</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                        @hasrole('ADMINISTRADOR')
                        <h1 class="text-6xl text-center font-serif">Guia: Herramientas de <br>administracion.</h1>
                            <section class="px-8 py-6 flex justify-center mx-52">
                                            <div class="bg-white shadow-md w-full max-w-3xl rounded-lg">
                                                <div tabindex="5" class="collapse collapse-arrow bg-base-200 rounded-lg">
                                                    <input type="checkbox" />
                                                    <div class="collapse-title text-lg font-medium">
                                                        "Gestionar Usuarios"
                                                    </div>
                                                    <div class="collapse-content bg-white">
                                                        <p>La sección "Gestión de Usuarios" es un panel exclusivo para administradores que permite el control completo sobre los perfiles registrados en la plataforma. Esta herramienta incluye:</p>
                                                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                                                            <li><strong>Tabla maestra de usuarios:</strong>
                                                                <ul class="list-circle list-inside ml-5 space-y-1">
                                                                    <li>Listado completo de todos los usuarios registrados</li>
                                                                    <li>Búsqueda instantánea por nombre o email</li>
                                                                </ul>
                                                            </li>
                                                            
                                                            <li><strong>Herramientas de administración:</strong>
                                                                <ul class="list-circle list-inside ml-5 space-y-1">
                                                                    <li>Edición de datos básicos (nombre, email, roles)</li>
                                                                    <li>Activación/desactivación de cuentas</li>
                                                                    <li>Restablecimiento de contraseñas</li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div tabindex="6" class="collapse collapse-arrow bg-base-200 rounded-lg">
                                                    <input type="checkbox" />
                                                    <div class="collapse-title text-lg font-medium">
                                                        "Progreso Por Usuario"
                                                    </div>
                                                    <div class="collapse-content bg-white">
                                                        <p>La sección "Progreso por Usuarios" es una herramienta administrativa diseñada para brindar soporte y seguimiento individualizado. Ofrece:</p>

                                                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                                                            <li><strong>Sistema de búsqueda avanzada:</strong>
                                                                <ul class="list-circle list-inside ml-5 space-y-1">
                                                                    <li>Campo de búsqueda por email exacto</li>
                                                                    <li>Botón de acción de búsqueda</li>
                                                                </ul>
                                                            </li>
                                                            
                                                            <li><strong>Panel de resultados:</strong>
                                                                <ul class="list-circle list-inside ml-5 space-y-1">
                                                                    <li>Tabla interactiva con historial completo de IMC del usuario</li>
                                                                    <li>Detalle por registro: fecha, peso, estatura, IMC calculado y categoría</li>
                                                                    <li>Opciones de gestión por registro:
                                                                        <ul class="list-square list-inside ml-5">
                                                                            <li>Modificar datos (peso/estatura)</li>
                                                                            <li>Eliminar registros incorrectos con doble confirmación</li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </li>

                                                            <li><strong>Resumen estadístico:</strong>
                                                                <ul class="list-circle list-inside ml-5 space-y-1">
                                                                    <li>Promedio histórico del IMC del usuario</li>
                                                                    <li>Distribución cuantitativa por categorías:
                                                                        <ul class="list-square list-inside ml-5">
                                                                            <li>Bajo peso</li>
                                                                            <li>Normal</li>
                                                                            <li>Sobrepeso</li>
                                                                            <li>Obesidad</li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                        <p>Esta herramienta está diseñada para brindar soporte técnico mientras mantiene la integridad de los datos históricos del sistema.</p>
                                                    </div>
                                                </div>

                                                <div tabindex="7" class="collapse collapse-arrow bg-base-200 rounded-lg">
                                                    <input type="checkbox" />
                                                    <div class="collapse-title text-lg font-medium">
                                                        "Logs del sistema"
                                                    </div>
                                                    <div class="collapse-content bg-white">
                                                        <p>La sección "Logs del Sistema" registra y muestra todas las actividades relevantes realizadas en la plataforma, proporcionando trazabilidad completa y seguridad. Esta herramienta está disponible exclusivamente para administradores.</p>

                                                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                                                            <li><strong>Registro detallado de actividades:</strong>
                                                                <ul class="list-circle list-inside ml-5 space-y-1">
                                                                    <li>Acciones de usuarios (registros de IMC)</li>
                                                                    <li>Operaciones administrativas (Edición/eliminación de usuarios y de registros de IMC)</li>
                                                                </ul>
                                                            </li>
                                                            
                                                            <li><strong>Panel de visualización avanzada:</strong>
                                                                <ul class="list-circle list-inside ml-5 space-y-1">
                                                                    <li>Detalle completo por registro:
                                                                        <ul class="list-square list-inside ml-5">
                                                                            <li>Fecha y hora exacta</li>
                                                                            <li>Modelo afectado</li>
                                                                            <li>Usuario que realizó la acción</li>
                                                                            <li>Descripción de la accion</li>
                                                                            <li>Detalle técnico del evento</li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                </section>
                        @endhasrole


            </div>
        </div>
    </div>
</x-app-layout>
