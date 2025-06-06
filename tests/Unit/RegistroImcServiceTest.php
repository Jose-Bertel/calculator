<?php

namespace Tests\Unit;

use App\Models\RegistroImc;
use App\Models\User;
use App\Services\RegistroImcService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class RegistroImcServiceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_crear_registro_si_no_existe_en_la_semana()
    {
        $peso = 70;
        $estatura = 1.75;
        $imc = $peso / ($estatura * $estatura);

        $registro = RegistroImcService::crearRegistro($this->user->id, $peso, $estatura, $imc);

        $this->assertInstanceOf(RegistroImc::class, $registro);
        $this->assertDatabaseHas('registro_imcs', [
            'user_id' => $this->user->id,
            'peso' => $peso,
            'estatura' => $estatura,
            'imc' => $imc,
        ]);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function no_debe_crear_registro_si_ya_existe_uno_en_la_semana()
    {
        $peso = 70;
        $estatura = 1.75;
        $imc = $peso / ($estatura * $estatura);

        // Crear el primer registro de esta semana
        RegistroImc::create([
            'user_id' => $this->user->id,
            'peso' => $peso,
            'estatura' => $estatura,
            'imc' => $imc,
            'created_at' => now(),
        ]);

        // Intentar crear otro en la misma semana
        $nuevoRegistro = RegistroImcService::crearRegistro($this->user->id, 72, 1.75, 23.5);

        $this->assertNull($nuevoRegistro); // No debe crearse
        $this->assertCount(1, RegistroImc::all()); // Solo debe haber un registro
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_eliminar_un_registro_existente()
    {
        $registro = RegistroImc::create([
            'user_id' => $this->user->id,
            'peso' => 70,
            'estatura' => 1.75,
            'imc' => 22.9,
        ]);

        $resultado = RegistroImcService::eliminarRegistro($registro->id);

        $this->assertTrue($resultado);
        $this->assertDatabaseMissing('registro_imcs', ['id' => $registro->id]);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function no_falla_si_intenta_eliminar_registro_inexistente()
    {
        $resultado = RegistroImcService::eliminarRegistro(999); // ID que no existe

        $this->assertFalse($resultado);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_listar_registros_por_usuario()
    {
        RegistroImc::factory()->count(3)->create(['user_id' => $this->user->id]);
        RegistroImc::factory()->count(2)->create(); // Otros usuarios

        $registros = RegistroImcService::listarPorUsuario($this->user->id);

        $this->assertCount(3, $registros);
        $this->assertTrue($registros->every(fn($r) => $r->user_id === $this->user->id));
    }   
    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_calcular_promedio_imc_por_usuario()
    {
        RegistroImc::create(['user_id' => $this->user->id, 'peso' => 70, 'estatura' => 1.75, 'imc' => 23]);
        RegistroImc::create(['user_id' => $this->user->id, 'peso' => 80, 'estatura' => 1.75, 'imc' => 26]);

        $promedio = RegistroImcService::promedioImcPorUsuario($this->user->id);

        $this->assertEquals(24.5, $promedio);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function devuelve_rango_correcto_para_valores_imc()
    {
        $this->assertEquals('Bajo peso', RegistroImcService::obtenerRangoIMC(17.5));
        $this->assertEquals('Normal', RegistroImcService::obtenerRangoIMC(22));
        $this->assertEquals('Sobrepeso', RegistroImcService::obtenerRangoIMC(27));
        $this->assertEquals('Obesidad', RegistroImcService::obtenerRangoIMC(32));
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_obtener_registro_por_id()
    {
        $registro = RegistroImc::create([
            'user_id' => $this->user->id,
            'peso' => 75,
            'estatura' => 1.75,
            'imc' => 24.5,
        ]);

        $resultado = RegistroImcService::getById($registro->id);

        $this->assertEquals($registro->id, $resultado->id);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function cuenta_correctamente_por_rango_imc()
    {
        RegistroImc::create(['user_id' => $this->user->id, 'peso' => 50, 'estatura' => 1.75, 'imc' => 16.3]); // Bajo
        RegistroImc::create(['user_id' => $this->user->id, 'peso' => 65, 'estatura' => 1.75, 'imc' => 21.2]); // Normal
        RegistroImc::create(['user_id' => $this->user->id, 'peso' => 80, 'estatura' => 1.75, 'imc' => 26.1]); // Sobrepeso
        RegistroImc::create(['user_id' => $this->user->id, 'peso' => 100, 'estatura' => 1.75, 'imc' => 32.7]); // Obesidad

        $conteo = RegistroImcService::conteoPorRangoImc($this->user->id);

        $this->assertEquals(1, $conteo['Bajo peso']);
        $this->assertEquals(1, $conteo['Normal']);
        $this->assertEquals(1, $conteo['Sobrepeso']);
        $this->assertEquals(1, $conteo['Obesidad']);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_actualizar_un_registro_imc()
    {
        $registro = RegistroImc::create([
            'user_id' => $this->user->id,
            'peso' => 70,
            'estatura' => 1.75,
            'imc' => 22.9,
        ]);

        RegistroImcService::actualizarRegistroImc($registro->id, 80, 1.75, 26.1);

        $registro->refresh();
        $this->assertEquals(80, $registro->peso);
        $this->assertEquals(26.1, $registro->imc);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_calcular_promedio_general_de_imc()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        RegistroImc::factory()->create(['user_id' => $user1->id, 'imc' => 22.5]);
        RegistroImc::factory()->create(['user_id' => $user2->id, 'imc' => 27.5]);

        $promedio = RegistroImcService::promedioGeneralImc();

        $this->assertNotNull($promedio);
        $this->assertEquals(25.0, round($promedio, 1));
    }
}
