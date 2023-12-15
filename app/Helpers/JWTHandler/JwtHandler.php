<?php
namespace Helpers\JWTHandler;

use Firebase\JWT\JWT;


class JwtHandler
{
    private string $secretKey = "0KHVltfBYRj9NpRYCfeSW4nt1UTtSyJ";

    public function generateToken($data)
    {
        log_message("info", "JwtHandler::generateToken init. With data: ".json_encode($data));
        $issuedAt = time();
        $expire = $issuedAt + 3600; // Expire dans 1 heure (vous pouvez ajuster selon vos besoins)

        $token = [
            "iat" => $issuedAt, // Timestamp de l'émission du token
            "exp" => $expire,   // Timestamp de l'expiration du token
            "data" => $data     // Données que vous souhaitez inclure dans le token
        ];
        log_message("info", "token : ".json_encode($token));
       return JWT::encode($token, $this->secretKey, 'HS256');
    }

    public function decodeToken($token)
    {
        try {
            $headers = 'HS256';
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return $decoded->data; // Retourne les données incluses dans le token
        } catch (\Exception $e) {
            // Gérer les erreurs de décodage (token expiré, invalide, etc.)
            return null;
        }
    }

    public function validateToken($token)
    {
        try {
            $headers = 'HS256';
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            
            $currentTimestamp = time();
            if ($decoded->exp < $currentTimestamp) {
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}