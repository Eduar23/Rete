<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Llama al seeder específico que creaste
        $this->call(UserSeeder::class);

        // Si quieres seguir usando factories para más datos, puedes hacerlo aquí también:
        // User::factory(10)->create();
    }
}

