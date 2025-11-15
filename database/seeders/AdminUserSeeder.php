<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les rôles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Créer l'utilisateur super admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Administrateur',
                'password' => Hash::make('passer123')
            ]
        );

        // Assigner le rôle super-admin
        $superAdmin->assignRole($superAdminRole);

        $this->command->info('Utilisateur super admin créé avec succès!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Mot de passe: passer123');
    }
}
