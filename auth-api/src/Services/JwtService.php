<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService {
    private static $secretKey = 'SUA_CHAVE_SECRETA_AQUI'; // coloque uma chave segura

    public static function generateToken($payload, $exp = 3600) {
        $now = time();
        $payload['iat'] = $now;
        $payload['exp'] = $now + $exp;

        return JWT::encode($payload, self::$secretKey, 'HS256');
    }

    public static function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}
