<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Buscar usuarios por nombre o email, y opcionalmente filtrar por roles.
     *
     * @param string $search
     * @param int $perPage
     * @param array $roles
     * @return LengthAwarePaginator
     */
    public static function buscar(string $search = '', int $perPage = 10, array $roles = []): LengthAwarePaginator
    {
        $query = User::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%"));

        if (!empty($roles)) {
            $query->whereHas('roles', function ($q) use ($roles) {
                $q->whereIn('name', $roles);
            });
        }

        return $query->paginate($perPage);
    }

    public static function getById(int $id): ?User
    {
        return User::find($id);
    }

    public static function upSert(array $data): User
    {
        if (isset($data['id'])) {
            $user = User::find($data['id']);
            if (!$user) {
                throw new \Exception("Usuario no encontrado.");
            }
            $user->name = $data['name'];
            $user->email = $data['email'];
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();
        } else {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
        return $user;
    }

    public static function assignRole(User $user, string $role): void
    {
        if (!$user->hasRole($role)) {
            $user->assignRole($role);
        }
    }

    public static function removeRole(User $user, string $role): void
    {
        if ($user->hasRole($role)) {
            $user->removeRole($role);
        }
    }

    public static function delete(User $user): void
    {
        $user->delete();
    }

    public static function actualizarNombre(int $id, string $name): void
    {
        $user = User::findOrFail($id);
        $user->name = $name;
        $user->save();
    }

    public static function actualizarEmail(int $id, string $email): void
    {
        $user = User::findOrFail($id);
        $user->email = $email;
        $user->save();
    }

    public static function actualizarPassword(int $id, string $password): void
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make($password);
        $user->save();
    }
    public static function obtenerIdPorEmail($valor)
    {
        return User::where('email', $valor)->first();
    }
}
