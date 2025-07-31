<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin kullanıcısı
        User::create([
            'name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@otel.com',
            'password' => Hash::make('password'),
            'phone' => '0555 123 45 67',
            'emergency_contact_name' => 'Acil Durum Kişisi',
            'emergency_contact_phone' => '0555 987 65 43',
            'address' => 'İstanbul, Türkiye',
            'role' => 'admin',
        ]);

        // Çalışan kullanıcısı
        User::create([
            'name' => 'Çalışan',
            'surname' => 'User',
            'email' => 'employee@otel.com',
            'password' => Hash::make('password'),
            'phone' => '0555 111 22 33',
            'emergency_contact_name' => 'Acil Durum Kişisi',
            'emergency_contact_phone' => '0555 444 55 66',
            'address' => 'İstanbul, Türkiye',
            'role' => 'employee',
        ]);

        $this->command->info('Kullanıcılar başarıyla oluşturuldu!');
        $this->command->info('Admin: admin@otel.com / password');
        $this->command->info('Çalışan: employee@otel.com / password');
    }
}
