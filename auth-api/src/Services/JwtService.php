<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private static $secretKey;

    public static function init()
    {
        self::$secretKey = $_ENV['JWT_SECRET'] ?? 'chave_fallback_insegura';
    }

    public static function generateToken($payload, $exp = 3600)
    {
        self::init();
        $now = time();
        $payload['iat'] = $now;
        $payload['exp'] = $now + $exp;
        return JWT::encode($payload, self::$secretKey, 'HS256');
    }

    public static function validateToken($token)
    {
        self::init();
        try {
            return JWT::decode($token, new Key(self::$secretKey, 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }

}

