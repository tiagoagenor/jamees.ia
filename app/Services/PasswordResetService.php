<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;
use Exception;

class PasswordResetService
{
    /**
     * Gerar token JWT para recuperação de senha
     */
    public static function generateToken(string $email): string
    {
        $usuario = Usuario::where('email', $email)->first();

        if (!$usuario) {
            throw new Exception('Usuário não encontrado');
        }

        $payload = [
            'email' => $email,
            'user_id' => $usuario->id,
            'iat' => time(),
            'exp' => time() + (15 * 60), // 15 minutos
            'purpose' => 'password_reset'
        ];

        return JWT::encode($payload, self::getSecretKey(), 'HS256');
    }

    /**
     * Validar token JWT
     */
    public static function validateToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key(self::getSecretKey(), 'HS256'));

            // Verificar se o token é para recuperação de senha
            if (!isset($decoded->purpose) || $decoded->purpose !== 'password_reset') {
                return ['valid' => false, 'error' => 'Token inválido para recuperação de senha'];
            }

            // Verificar se o usuário ainda existe
            $usuario = Usuario::where('email', $decoded->email)->first();
            if (!$usuario) {
                return ['valid' => false, 'error' => 'Usuário não encontrado'];
            }

            return [
                'valid' => true,
                'email' => $decoded->email,
                'user_id' => $decoded->user_id,
                'expires_at' => date('Y-m-d H:i:s', $decoded->exp),
                'usuario' => $usuario
            ];

        } catch (Exception $e) {
            Log::error('Erro ao validar token de recuperação de senha: ' . $e->getMessage());
            return ['valid' => false, 'error' => 'Token inválido ou expirado'];
        }
    }

    /**
     * Gerar URL de recuperação
     */
    public static function generateResetUrl(string $email): string
    {
        $token = self::generateToken($email);
        return env('JAMEES_SITE_URL', 'https://jamees.com/') . 'reset-password?token=' . $token;
    }

    /**
     * Obter chave secreta do .env
     */
    private static function getSecretKey(): string
    {
        return env('PASSWORD_RESET_SECRET', 'JameesPasswordReset2025SecretKey');
    }

    /**
     * Obter tempo de expiração em minutos
     */
    public static function getExpirationTime(): int
    {
        return env('PASSWORD_RESET_EXPIRES_MINUTES', 15);
    }
}
