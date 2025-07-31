<?php

namespace App\Domains\User\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\User\Entities\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $adminEmail = 'admin@upmanager.com';
        
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => $adminEmail,
                'password' => Hash::make('admin123'),
                'phone' => '(11) 99999-9999',
                'position' => 'Administrador do Sistema',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Usuário administrador criado com sucesso!');
            $this->command->info('Email: ' . $adminEmail);
            $this->command->info('Senha: admin123');
        } else {
            $this->command->info('Usuário administrador já existe.');
        }
    }
}
