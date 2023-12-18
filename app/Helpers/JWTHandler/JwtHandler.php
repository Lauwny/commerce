<?php
namespace Helpers\JWTHandler;

use App\Models\TokenModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
            "data" => $data     // Données à inclure dans le token
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

    public function revokeToken($userId, $tokenValue) {
        $tokenModel = new TokenModel();
        log_message("info", "JwtHandler::revokeToken init. With data: " . json_encode($tokenValue));

        // Check if the token is not already revoked
        if (!$this->isRevoked($tokenValue)) {
            try {
                log_message("info", "JwtHandler::revokeToken. Token revoked");
                $tokenModel->insertRevokedToken($tokenValue, $userId);
                log_message("info", "JwtHandler::revokeToken code: 1");
                return 1; // Success
            } catch (\ReflectionException $e) {
                log_message("error", "JwtHandler::revokeToken  ReflectionException: $e");
                log_message("info", "JwtHandler::revokeToken code: 2");
                return 2; // Error
            }
        } else {
            log_message("info", "JwtHandler::revokeToken. Token $tokenValue \n is already revoked");
            log_message("info", "JwtHandler::revokeToken code: 3");
            return 3; // Already revoked
        }
    }

    public function isRevoked($token): bool {
        $tokenModel = new TokenModel();
        return $tokenModel->where('tokenValue', $token)->first() !== null;
    }

    /**
     * Return the id based on the token if the id exist
     *
     * @param $token given user token
     * @return int return the id if exist, else return 0
     */
    public function getUserId($token): int {
        return $this->decodeToken($token)->userId ?? 0;
    }
}
