<?php

// Load Laravel autoloader
require __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get Supabase credentials
$supabaseUrl = $_ENV['SUPABASE_URL'];
$supabaseKey = $_ENV['SUPABASE_ANON_KEY'];

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

echo "\n========================================\n";
echo "Registrando usuarios en Supabase Auth\n";
echo "========================================\n\n";

foreach ($users as $userData) {
    echo "Registrando: {$userData['email']}\n";

    try {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $supabaseUrl . '/auth/v1/signup',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'email' => $userData['email'],
                'password' => $userData['password'],
                'user_metadata' => [
                    'name' => $userData['name'],
                ],
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'apikey: ' . $supabaseKey,
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            echo "  ✗ Error CURL: {$error}\n\n";
            continue;
        }

        $data = json_decode($response, true);

        if ($httpCode === 200 || $httpCode === 201) {
            echo "  ✓ Usuario registrado en Supabase Auth\n";
            if (isset($data['user']['id'])) {
                echo "  - ID de Supabase: {$data['user']['id']}\n";
            }
        } else {
            echo "  ✗ Error HTTP {$httpCode}\n";
            if (isset($data['error_description'])) {
                echo "  - {$data['error_description']}\n";
            } elseif (isset($data['message'])) {
                echo "  - {$data['message']}\n";
            }
        }
    } catch (Exception $e) {
        echo "  ✗ Excepción: " . $e->getMessage() . "\n";
    }

    echo "\n";
}

echo "========================================\n";
echo "Proceso completado\n";
echo "========================================\n\n";

