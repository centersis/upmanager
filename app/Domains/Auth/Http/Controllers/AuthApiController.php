<?php

namespace App\Domains\Auth\Http\Controllers;

use App\Shared\Http\Controllers\Controller;
use App\Domains\Auth\Http\Requests\LoginApiRequest;
use App\Domains\User\Entities\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class AuthApiController extends Controller
{
    /**
     * Handle user login and return JWT token
     * 
     * @param LoginApiRequest $request
     * @return JsonResponse
     */
    public function login(LoginApiRequest $request): JsonResponse
    {
        try {
            // Attempt authentication
            $request->authenticate();

            $user = Auth::user();
            
            // Update last login timestamp
            $user->updateLastLogin();

            // Create token
            $token = $user->createToken($request->getDeviceName())->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'role_display' => $user->role_display,
                        'is_active' => $user->is_active,
                        'phone' => $user->phone,
                        'position' => $user->position,
                        'last_login_at' => $user->last_login_at?->toISOString(),
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciais inválidas',
                'errors' => $e->errors()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get authenticated user information
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'role_display' => $user->role_display,
                        'is_active' => $user->is_active,
                        'phone' => $user->phone,
                        'position' => $user->position,
                        'last_login_at' => $user->last_login_at?->toISOString(),
                        'email_verified_at' => $user->email_verified_at?->toISOString(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Logout user and revoke token
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user) {
                // Revoke current token
                $request->user()->currentAccessToken()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Logout realizado com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Logout from all devices (revoke all tokens)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user) {
                // Revoke all tokens
                $user->tokens()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Logout de todos os dispositivos realizado com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Refresh token (get new token)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            // Create new token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Token renovado com sucesso',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }
}
