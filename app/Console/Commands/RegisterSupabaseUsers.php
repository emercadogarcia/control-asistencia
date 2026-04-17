<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class RegisterSupabaseUsers extends Command
{
    protected $signature = 'register:supabase-users';
    protected $description = 'Register demo users in Supabase Auth';

    public function handle()
    {
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_ANON_KEY');

        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@asistencia.local',
                'password' => 'password',
            ],
            [
                'name' => 'Usuario Demo',
                'email' => 'demo@asistencia.local',
                'password' => 'password',
            ],
        ];

        foreach ($users as $userData) {
            $this->info("\n========================================");
            $this->info("Registrando: {$userData['email']}");
            $this->info("========================================");

            try {
                // Verificar si el usuario ya existe en la BD local
                $existingUser = User::where('email', $userData['email'])->first();
                if ($existingUser) {
                    $this->warn("✓ Usuario ya existe en la BD local");
                    continue;
                }

                // Intentar registrar en Supabase Auth
                $response = Http::withHeader('apikey', $supabaseKey)
                    ->post($supabaseUrl . '/auth/v1/signup', [
                        'email' => $userData['email'],
                        'password' => $userData['password'],
                        'user_metadata' => [
                            'name' => $userData['name'],
                        ],
                    ]);

                $this->line("Status: " . $response->status());

                if ($response->successful()) {
                    $data = $response->json();
                    $supabaseUser = $data['user'] ?? null;

                    if ($supabaseUser) {
                        // Crear en BD local
                        User::updateOrCreate(
                            ['email' => $userData['email']],
                            [
                                'name' => $userData['name'],
                                'supabase_id' => $supabaseUser['id'],
                                'estado' => 1,
                            ]
                        );

                        $this->info("✓ Usuario registrado exitosamente");
                    } else {
                        $this->error("✗ Usuario creado en Supabase pero sin ID");
                    }
                } else {
                    $error = $response->json('error_description', $response->json('message', 'Error desconocido'));
                    $this->error("✗ Error: " . $error);
                }
            } catch (\Exception $e) {
                $this->error("✗ Excepción: " . $e->getMessage());
            }
        }

        $this->info("\n========================================");
        $this->info("Proceso completado");
        $this->info("========================================\n");
    }
}
