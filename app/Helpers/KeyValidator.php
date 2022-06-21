<?php

namespace App\Helpers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use \Firebase\JWT\JWT;

use App\Controllers\BaseController;

class KeyValidator
{
    private $key = "maclef";

    private function getKey()
    {
        return $this->key;
    }

    function generateKey($userdata){
        $key = $this->getKey();

        $iat = time(); // current timestamp value
        $nbf = $iat + 10;
        $exp = $iat + 3600;

        $payload = array(
            "iss" => "The_claim",
            "aud" => "The_Aud",
            "iat" => $iat, // issued at
            "nbf" => $nbf, //not before in seconds
            "exp" => $exp, // expire time in seconds
            "data" => array("idUser"=>$userdata['userId'], "mailUser"=>$userdata['userUsername'], "userId"=>$userdata['userId']),
        );
        return JWT::encode($payload, $key, 'HS256');
    }

    public function decodeToken($token){
        return JWT::decode($token, $this->key)->data;
    }

}
