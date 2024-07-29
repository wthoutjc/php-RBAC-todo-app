<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin_role = Role::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'name' => 'admin',
        ]);

        Role::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'name' => 'user',
        ]);

        User::factory(1)->create(
            ['role_id' => $admin_role->id]
        );
    }
}
