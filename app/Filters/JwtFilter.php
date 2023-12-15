<?php

// app/Filters/JwtFilter.php

namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use Firebase\JWT\JWT;

class JwtFilter implements FilterInterface
{
    use ResponseTrait;

    private $secretKey = "0KHVltfBYRj9NpRYCfeSW4nt1UTtSyJ";

    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifiez si la route nécessite une authentification JWT
        if (in_array($request->uri->getPath(), $arguments['except'])) {
            return;
        }

        // Générez un token JWT et ajoutez-le à l'en-tête Authorization
        $token = $this->generateToken(['user_id' => 123, 'username' => 'john_doe']);
        $request = $request->withHeader('Authorization', 'Bearer ' . $token);

        return $request;
    }

    private function generateToken($data)
    {
        $issuedAt = time();
        $expire = $issuedAt + 3600; // Expire dans 1 heure (vous pouvez ajuster selon vos besoins)

        $token = [
            "iat" => $issuedAt, // Timestamp de l'émission du token
            "exp" => $expire,   // Timestamp de l'expiration du token
            "data" => $data     // Données que vous souhaitez inclure dans le token
        ];

        return JWT::encode($token, $this->secretKey, 'HS256');
    }

    public function after(RequestInterface $request, $response, $arguments = null)
    {
        // Vous pouvez ajouter des actions après la réponse si nécessaire
        return $response;
    }
}