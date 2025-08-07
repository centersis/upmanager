<?php

namespace App\Domains\Auth\Http\Controllers;

use App\Shared\Http\Controllers\Controller;
use App\Domains\Auth\Http\Requests\ForgotPasswordApiRequest;
use App\Domains\Auth\Http\Requests\ResetPasswordApiRequest;
use App\Domains\Auth\Http\Requests\ChangePasswordApiRequest;
use App\Domains\User\Entities\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetApiController extends Controller
{
    /**
     * Send password reset link to user email
     * 
     * @param ForgotPasswordApiRequest $request
     * @return JsonResponse
     */
    public function sendResetLink(ForgotPasswordApiRequest $request): JsonResponse
    {
        try {

            // Check if user exists and is active
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                // Don't reveal if user exists or not for security
                return response()->json([
                    'success' => true,
                    'message' => 'Se o email estiver cadastrado, você receberá um link para redefinir sua senha.'
                ], 200);
            }

            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta conta está inativa. Entre em contato com o administrador.',
                ], 403);
            }

            // Send password reset link
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Link de redefinição de senha enviado para seu email.'
                ], 200);
            }

            // For security, don't reveal specific errors
            return response()->json([
                'success' => true,
                'message' => 'Se o email estiver cadastrado, você receberá um link para redefinir sua senha.'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor.',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Validate password reset token
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function validateToken(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token' => ['required', 'string'],
                'email' => ['required', 'string', 'email'],
            ], [
                'token.required' => 'O token é obrigatório.',
                'email.required' => 'O campo email é obrigatório.',
                'email.email' => 'O campo email deve ser um endereço de email válido.',
            ]);

            // Check if the token is valid
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido ou expirado.',
                ], 400);
            }

            // Verify token using Laravel's password broker
            $broker = Password::broker();
            $token = $broker->getRepository()->exists($user, $request->token);

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido ou expirado.',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Token válido.',
                'data' => [
                    'email' => $user->email,
                    'name' => $user->name,
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor.',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Reset user password with token
     * 
     * @param ResetPasswordApiRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordApiRequest $request): JsonResponse
    {
        try {

            // Attempt to reset the user's password
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    // Revoke all existing tokens for security
                    $user->tokens()->delete();

                    event(new PasswordReset($user));
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Senha redefinida com sucesso. Faça login com sua nova senha.'
                ], 200);
            }

            // Handle different error statuses
            $message = match ($status) {
                Password::INVALID_TOKEN => 'Token inválido ou expirado.',
                Password::INVALID_USER => 'Usuário não encontrado.',
                default => 'Erro ao redefinir senha. Tente novamente.'
            };

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor.',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Change password for authenticated user
     * 
     * @param ChangePasswordApiRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordApiRequest $request): JsonResponse
    {
        try {

            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado.'
                ], 401);
            }

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Senha atual incorreta.',
                    'errors' => [
                        'current_password' => ['Senha atual incorreta.']
                    ]
                ], 422);
            }

            // Update password
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            // Optionally revoke all other tokens for security
            if ($request->boolean('logout_other_devices', false)) {
                $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Senha alterada com sucesso.'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor.',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }
}
