<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_puede_crear_un_usuario_nuevo()
    {
        $data = [
            'name' => 'Juan Perez',
            'email' => 'juan@example.com',
            'password' => 'secret123',
            'birthdate' => '1990-01-01',  // Campo obligatorio agregado
        ];

        $user = UserService::upSert($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Juan Perez', $user->name);
        $this->assertEquals('juan@example.com', $user->email);
        $this->assertNotEmpty($user->password);
        $this->assertEquals('1990-01-01', $user->birthdate);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_puede_buscar_usuarios_basico()
    {
        // Crear 3 usuarios de prueba
        \App\Models\User::factory()->count(3)->create();

        // Llamar al método buscar sin filtros
        $resultado = \App\Services\UserService::buscar();

        // Verificar que el resultado es una instancia de paginador
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $resultado);

        // Verificar que trae usuarios
        $this->assertGreaterThanOrEqual(3, $resultado->total());
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_puede_buscar_usuarios_con_texto()
    {
        // Crear usuarios con nombres y emails distintos
        \App\Models\User::factory()->create(['name' => 'Juan Perez', 'email' => 'juan@example.com']);
        \App\Models\User::factory()->create(['name' => 'Maria Gomez', 'email' => 'maria@example.com']);

        // Buscar con texto 'Juan'
        $resultado = \App\Services\UserService::buscar('Juan');

        // Verificar que devuelve solo 1 usuario
        $this->assertCount(1, $resultado->items());

        // Confirmar que el usuario encontrado es 'Juan Perez'
        $this->assertEquals('Juan Perez', $resultado->items()[0]->name);

        // Buscar con texto 'example.com' (debería traer ambos)
        $resultado2 = \App\Services\UserService::buscar('example.com');
        $this->assertGreaterThanOrEqual(2, $resultado2->total());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_puede_buscar_usuarios_filtrando_por_roles()
    {
        // Crear los roles necesarios para la prueba
        Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
        Role::firstOrCreate(['name' => 'USUARIO']);

        // Crear usuarios
        $admin = \App\Models\User::factory()->create(['name' => 'Admin User']);
        $user = \App\Models\User::factory()->create(['name' => 'Regular User']);

        // Asignar roles
        $admin->assignRole('ADMINISTRADOR');
        $user->assignRole('USUARIO');

        // Buscar usuarios con rol 'ADMINISTRADOR'
        $resultadoAdmin = \App\Services\UserService::buscar('', 10, ['ADMINISTRADOR']);
        $this->assertCount(1, $resultadoAdmin->items());
        $this->assertEquals('Admin User', $resultadoAdmin->items()[0]->name);

        // Buscar usuarios con rol 'USUARIO'
        $resultadoUser = \App\Services\UserService::buscar('', 10, ['USUARIO']);
        $this->assertCount(1, $resultadoUser->items());
        $this->assertEquals('Regular User', $resultadoUser->items()[0]->name);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_puede_obtener_usuario_por_id()
    {
        $usuario = \App\Models\User::factory()->create();

        $resultado = \App\Services\UserService::getById($usuario->id);

        $this->assertNotNull($resultado);
        $this->assertEquals($usuario->id, $resultado->id);
        $this->assertEquals($usuario->email, $resultado->email);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_up_sert_crea_y_actualiza_usuario(): void
    {
        // Crear usuario nuevo
        $creado = UserService::upSert([
            'name' => 'Luis',
            'email' => 'luis@example.com',
            'birthdate' => '1990-04-20',
            'password' => 'secret123',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $creado->id,
            'name' => 'Luis',
            'email' => 'luis@example.com',
        ]);

        // Actualizar usuario existente
        $actualizado = UserService::upSert([
            'id' => $creado->id,
            'name' => 'Luis Miguel',
            'email' => 'luismiguel@example.com',
            'birthdate' => '1991-05-10', // Aquí estaba el error
            'password' => 'nuevaclave456',
        ]);

        $this->assertEquals('Luis Miguel', $actualizado->name);
        $this->assertEquals('luismiguel@example.com', $actualizado->email);
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_assign_role_asigna_un_rol_al_usuario(): void
    {
        // Aseguramos que el rol exista
        Role::firstOrCreate(['name' => 'ADMINISTRADOR']);

        $usuario = User::factory()->create([
            'birthdate' => '1990-01-01',
        ]);

        $this->assertFalse($usuario->hasRole('ADMINISTRADOR'));

        UserService::assignRole($usuario, 'ADMINISTRADOR');

        $this->assertTrue($usuario->fresh()->hasRole('ADMINISTRADOR'));
    }
    public function test_remove_role_elimina_un_rol_al_usuario(): void
{
    // Crear el rol y el usuario
    $rol = Role::firstOrCreate(['name' => 'USUARIO']);
    $usuario = User::factory()->create([
        'birthdate' => '1990-01-01',
    ]);

    // Asignar el rol
    $usuario->assignRole($rol);
    $this->assertTrue($usuario->hasRole('USUARIO'));

    // Remover el rol
    UserService::removeRole($usuario, 'USUARIO');
    $this->assertFalse($usuario->fresh()->hasRole('USUARIO'));
}
    #[\PHPUnit\Framework\Attributes\Test]
public function puede_eliminar_un_usuario()
{
    $user = User::factory()->create();
    UserService::delete($user);
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
}

    #[\PHPUnit\Framework\Attributes\Test]
public function puede_actualizar_el_nombre_del_usuario()
{
    $user = User::factory()->create(['name' => 'Nombre Viejo']);
    UserService::actualizarNombre($user->id, 'Nombre Nuevo');
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Nombre Nuevo']);
}

    #[\PHPUnit\Framework\Attributes\Test]
public function puede_actualizar_el_email_del_usuario()
{
    $user = User::factory()->create(['email' => 'viejo@example.com']);
    UserService::actualizarEmail($user->id, 'nuevo@example.com');
    $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'nuevo@example.com']);
}

    #[\PHPUnit\Framework\Attributes\Test]
public function puede_actualizar_la_contraseña_del_usuario()
{
    $user = User::factory()->create(['password' => bcrypt('oldpassword')]);
    UserService::actualizarPassword($user->id, 'newpassword');
    $user->refresh();
    $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword', $user->password));
}

    #[\PHPUnit\Framework\Attributes\Test]
public function puede_obtener_usuario_por_email()
{
    $user = User::factory()->create(['email' => 'correo@example.com']);
    $resultado = UserService::obtenerIdPorEmail('correo@example.com');
    $this->assertNotNull($resultado);
    $this->assertEquals($user->id, $resultado->id);
}



}
