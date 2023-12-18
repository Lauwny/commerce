<?php

namespace App\Controllers;

use App\Models\TokenModel;
use Helpers\JWTHandler\JwtHandler;
use CodeIgniter\RESTful\ResourceController;
use Helpers\RestResponse\RestResponse;
use App\Models\UtilisateurModel;


class AuthController extends ResourceController
{
    public $userModel;
    protected $token;


    public function login()
    {
        $this->logger->info("AuthController::login initialized");

        $json = $this->request->getJSON();

        $this->logger("info", json_encode($json));

        $rules = [
            'login' => "required",
            'password' => 'required',
        ];
        $this->userModel = new UtilisateurModel();

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();

            $restResponse = new RestResponse(["code" => "validation_error", "message" => "Auth validation issue.", "data" => ["serv_err" => $errors]]);
            return $this->respond(json_decode($restResponse->build()), 500);
        }
        $validatedData = $this->validator->getValidated();


        $userlogin = $validatedData['login'];
        $userpassword = $validatedData['password'];

        $userpassword = hash('sha256', $userpassword);

        $userloginType = $this->getUserLoginType($userlogin);

        if ($userloginType == "mail") {
            $this->logger->info("type MAIL");
            $user = $this->userModel->where('mail', $userlogin)->first();
        } else {
            $this->logger->info("type USERNAME");
            $this->logger("info", "lol : $userlogin");
            $user = $this->userModel->where('login', $userlogin)->first();
        }

        if ($user) {
            $this->logger->info("user : " . json_encode($user));
            if ($userpassword == $user->password) {
                $this->logger->info("auth for user $userlogin");
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
                $this->logger->error("user $user->login failed to login");
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

        $this->logger->info("AuthController::logout initialized");

        $requestData = $this->request->getJSON();
        $token = $requestData->token;
        $userId = $requestData->userId;
        if (!$token) {
            $restResponse = new RestResponse(["code" => "token_not_provided", "message" => "Token not provided", "data" => ""]);
            return $this->respond(json_decode($restResponse->build()), 400);
        }

        $jwtHandler = new JwtHandler();

        if ($jwtHandler->validateToken($token)) {
            $tokenRevokingCode = $jwtHandler->revokeToken($userId, $token);
            if($tokenRevokingCode == 1){
                $restResponse = new RestResponse(["code" => "logout_success", "message" => "Logout successful"]);
                return $this->respond(json_decode($restResponse->build()), 200);
            }elseif($tokenRevokingCode == 2){
                $restResponse = new RestResponse(["code" => "error_token_revoking", "message" => "Error during revokation of token"]);
                $this->respond(json_decode($restResponse->build()), 500);
            }else{
                $restResponse = new RestResponse(["code" => "error_token_already_revoked", "message" => "Token is already revoked"]);
                return $this->respond($restResponse->build(), 409);
            }
        } else {
            $restResponse = new RestResponse(["code" => "invalid_token", "message" => "Invalid token", "data" => ""]);
            return $this->respond(json_decode($restResponse->build()), 401);
        }
    }

    private function getUserLoginType($userlogin): string
    {
        return str_contains($userlogin, '@') ? 'mail' : 'username';
    }
}
