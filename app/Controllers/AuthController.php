<?php

namespace App\Controllers;

use Helpers\JWTHandler\JwtHandler;

use App\Models\UtilisateurModel;
use Helpers\RestResponse\RestResponse;

use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    public $userModel;
    protected $selfresponse;

    protected $token;

    private $options = [
        'cost' => 1,
        ];

    public function login()
    {
        $this->logger("info", "AuthController initialized");

        $this->userModel = new UtilisateurModel();

        $userloginType = "login";
        $userlogin = $this->request->getPost('username');
        $userpassword = $this->request->getPost('password');

        $userpassword = hash('sha256', $userpassword);

        if (str_contains($userlogin, "@")) {
            $userloginType = "mail";
        }
        if ($userloginType == "mail") {
            $this->logger("info", "type MAIL");
            $user = $this->userModel->where('mail', $userlogin)->first();
        } else {
            $this->logger("info", "type USERNAME");
            $user = $this->userModel->where('login', $userlogin)->first();
        }

        if ($user) {
            $this->logger("info", "user : " . json_encode($user));
            if ($userpassword == $user->password) {
                $this->logger("info", "auth for user $userlogin");
                $this->userModel->setLastLoginDate($user->id, ['lastLogin' => date("Y-m-d H:i:s")]);
                $jwtHandler = new JwtHandler();
                $this->token = $jwtHandler->generateToken(
                    [
                        "userId" => $user->id,
                        "teethtype" => "simple"
                    ]
                );

                $restResponse = new RestResponse(["code" => "auth_success", "message" => "Authentication successful", "data" => ["jwt_token" => $this->token]]);
                return $this->respond(json_decode($restResponse->build()), 200);
            } else {
                $this->logger("error", "user $user->login failed to login");
                $restResponse = new RestResponse(["code" => "wrong_pwd_uname", "message" => "Wrong password or username", "data" => ""]);
                return $this->respond(json_decode($restResponse->build()), 401);
            }
        } else {
            $restResponse = new RestResponse(["code" => "user_not_found", "message" => "User not found", "data" => ""]);
            return $this->respond(json_decode($restResponse->build()), 404);
        }
    }

    public function logout()
    {
        $requestData = $this->request->getJSON();
        $token = $requestData->token;

        if (!$token) {
            $restResponse = new RestResponse(["code" => "token_not_provided", "message" => "Token not provided", "data" => ""]);
            return $this->respond(json_decode($restResponse->build()), 400);
        }

        $jwtHandler = new JwtHandler();

        if ($jwtHandler->validateToken($token)) {
            $restResponse = new RestResponse(["code" => "logout_success", "message" => "Logout successful", "data" => ""]);
            return $this->respond(json_decode($restResponse->build()), 200);
        } else {
            $restResponse = new RestResponse(["code" => "invalid_token", "message" => "Invalid token", "data" => ""]);
            return $this->respond(json_decode($restResponse->build()), 401);
        }
    }
}