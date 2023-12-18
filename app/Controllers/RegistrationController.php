<?php

namespace App\Controllers;

use App\Models\UserProfileModel;
use App\Models\UtilisateurModel;
use CodeIgniter\RESTful\ResourceController;
use Helpers\RestResponse\RestResponse;

class RegistrationController extends ResourceController
{
    private UtilisateurModel $userModel;
    private UserProfileModel $userProfileModel;

    protected $token;

    public function createUser()
    {
        $this->logger->info("RegistrationController::createUser initialized");

        $this->userModel = new UtilisateurModel();

        $requestData = $this->request->getJSON();
        $username = htmlspecialchars($requestData->username);
        $mail = htmlspecialchars($requestData->mail);
        $password = $requestData->password;
        $name = htmlspecialchars($requestData->name);
        $firstname = htmlspecialchars($requestData->firstname);

        if (empty($username) || empty($mail) || empty($password)) {
            $restResponse = new RestResponse([
                "code" => "invalid_request",
                "message" => "Username, mail, and password are required fields",
                "data" => ""
            ]);
            return $this->respond(json_decode($restResponse->build()), 400);
        }

        // 1. Hash the password
        $hashedPassword = hash('sha256', $password);

// 2. Check if username or mail is available
        $existingChecker = $this->isUsernameOrMailAvailable($username, $mail);

        // 3. If either mail or username is not available, return appropriate response
        switch ($existingChecker) {
            case 0:
                $code = "username_and_mail_taken";
                $message = "Username and mail are already taken";
                break;
            case 1:
                $code = "mail_taken";
                $message = "Mail is already taken";
                break;
            case 2:
                $code = "username_taken";
                $message = "Username is already taken";
                break;
            case 3:
                // No conflict
                break;
            default:
                $code = "unknown_error";
                $message = "An unknown error occurred";
                break;
        }

        // Return response if conflict
        if (isset($code)) {
            $restResponse = new RestResponse([
                "code" => $code,
                "message" => $message,
                "data" => ""
            ]);
            return $this->respond(json_decode($restResponse->build()), 409);
        }

        // 4. Proceed with user creation
        $userData = [
            'login' => $username,
            'password' => $hashedPassword,
            'mail' => $mail,
            'createdAt' => date('Y-m-d H:i:s'),
        ];

        if ($this->userModel->createNewUser($userData)) {
            $lastUserId = $this->userModel->insertID();

            $userFileDatas = [
                'idUser' => $lastUserId,
                'name' => $name,
                'firstname' => $firstname,
                'createdAt' => date('Y-m-d H:i:s'),
            ];

            // 5. Create user profile
            if (intval($this->createUserFile($userFileDatas) > 0)) {
                $this->logger->info("User $username profile created.");
                $this->logger->info("User $username created.");
                $restResponse = new RestResponse([
                    "code" => "user_created_successful",
                    "message" => "The user and profile have been created successfully",
                    "data" => ""
                ]);
                return $this->respond(json_decode($restResponse->build()), 200);
            } else {
                $this->logger->error("User $username profile not created.");
            }
        } else {
            $this->logger->error("User $username not created.");
        }

        // Return error response if any step fails
        $restResponse = new RestResponse([
            "code" => "user_creation_error",
            "message" => "An error occurred during user creation",
            "data" => ""
        ]);
        return $this->respond(json_decode($restResponse->build()), 404);
    }

    public function createUserFile($userDatas = [])
    {
        $this->logger("info", "RegistrationController::createUserFile initialized");

        $this->userProfileModel = new UserProfileModel();

        return $this->userProfileModel->createNewUserProfile($userDatas);
    }

    private function isUsernameOrMailAvailable($username, $mail)
    {
        // Check if the username and mail are available in the database
        $existingMail = $this->userModel->where('mail', $mail)->first();
        $existingUser = $this->userModel->where('login', $username)->first();
        if ($existingMail && $existingUser) {
            return 0;
        } elseif ($existingMail) {
            return 1;
        } elseif ($existingUser) {
            return 2;
        } else {
            return 3;
        }
    }
}
