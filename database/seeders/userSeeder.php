<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();
        // Administrador
        $admin = User::create(
            [   'email' => 'josebertel2004@gmail.com',
                'email_verified_at' => now(),
                'birthdate' => '2004-04-29',
                'name' => 'Jose Alberto Bertel Martinez',
                'password' => Hash::make('Admin123!'),
            ]
        );
        $admin->assignRole('ADMINISTRADOR');

        // $estudiante = User::create(
        //     [   'email' => $faker->unique()->safeEmail(),
        //         'email_verified_at' => now(),
        //         'name' => $faker->name(),
        //         'password' => Hash::make('provicional'),
        //     ]
        // );
        // $estudiante->assignRole('USUARIO');
    }
}
