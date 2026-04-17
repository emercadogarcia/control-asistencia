<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class AuthController extends Controller
{
    private $supabaseUrl;
    private $supabaseKey;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseKey = env('SUPABASE_ANON_KEY');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            // Intentar autenticar con Supabase Auth
            $response = Http::withHeader('apikey', $this->supabaseKey)
                ->post($this->supabaseUrl . '/auth/v1/token?grant_type=password', [
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $supabaseUser = $data['user'] ?? null;

                if (!$supabaseUser) {
                    return response()->json([
                        'message' => 'Las credenciales no son válidas.',
                    ], 401);
                }

                // Buscar o crear usuario en la BD local
                $user = User::updateOrCreate(
                    ['email' => $validated['email']],
                    [
                        'name' => $supabaseUser['user_metadata']['name'] ?? $supabaseUser['email'] ?? 'Usuario',
                        'email' => $supabaseUser['email'],
                        'supabase_id' => $supabaseUser['id'],
                    ]
                );

                // Crear token de sesión local para la app
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message' => 'Autenticación exitosa',
                    'token' => $token,
                    'supabase_token' => $data['access_token'],
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'message' => 'Las credenciales no son válidas.',
                    'error' => $response->json('error_description', 'Error desconocido'),
                ], 401);
            }
        } catch (\Exception $e) {
            \Log::error('Auth error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error en la autenticación. Por favor, intenta de nuevo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Sesión cerrada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        try {
            // Registrar en Supabase Auth
            $response = Http::withHeader('apikey', $this->supabaseKey)
                ->post($this->supabaseUrl . '/auth/v1/signup', [
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'user_metadata' => [
                        'name' => $validated['name'],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $supabaseUser = $data['user'] ?? null;

                if (!$supabaseUser) {
                    return response()->json([
                        'message' => 'Error al crear el usuario en Supabase.',
                    ], 400);
                }

                // Crear usuario en la BD local
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'supabase_id' => $supabaseUser['id'],
                    'estado' => 1,
                ]);

                return response()->json([
                    'message' => 'Usuario registrado exitosamente',
                    'user' => $user,
                ], 201);
            } else {
                $error = $response->json('error_description', $response->json('message', 'Error desconocido'));
                return response()->json([
                    'message' => 'Error al registrar: ' . $error,
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Register error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error en el registro. Por favor, intenta de nuevo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function user(Request $request)
    {
        try {
            return response()->json($request->user());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
