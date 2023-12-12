<?php

namespace App\Controllers;

use App\Models\UserProfileModel;
use App\Models\UtilisateurModel;
use CodeIgniter\Controller;

class RegistrationController extends BaseController
{
    //user id login password mail idUserProfil createdAt modifiedAt lastLogin
    //user profile 	id	name	firstname	birthdate	sexe	profilePicPath	idAdress	createdAt	modifiedAt

    private UtilisateurModel $userModel;
    private UserProfileModel $userProfileModel;

    private UtilisateurModel $userRoleModel;

    public function createUser()
    {
        $this->logger("info", "RegistrationController::createUser initialized");

        $this->userModel = new UtilisateurModel();

        $username = $this->request->getPost('username');
        $mail = $this->request->getPost('mail');
        $password = $this->request->getPost('password');
        $name = $this->request->getPost('name');
        $firstname = $this->request->getPost('firstname');

        if (!empty($username) || !empty($mail) || !empty($password)) {
            // 1. Hash the password
            $hashedPassword = hash('sha256', $password);

            // 2. Check if username or mail is available
            $existingChecker = $this->isUsernameOrMailAvailable($username, $mail);


            //3. If both mail and username are available,
            //proceed with user creation and call the insert method of UtilisateurModel
            if (!$existingChecker == 0) {
                $userData = [
                    'login' => $username,
                    'password' => $hashedPassword,
                    'mail' => $mail,
                    'createdAt' => date('Y-m-d H:i:s'),
                ];
                $this->userModel->insert($userData);
                $lastUserId = $this->userModel->insertID();

                $userFileDatas = [
                    'name' => $name,
                    'firstname' => $firstname
                ];
                if(intval($this->createUserFile($lastUserId, $userFileDatas) > 0)){
                    session()->set('userId', $lastUserId);
                    session()->set('loggedIn', true);
                    return redirect()->to('/testlog');
                }
            }
        }
    }

    public function createUserFile($lastUserId, $userDatas = [])
    {
        $this->logger("info", "RegistrationController::createUserFile initialized");

        $this->userProfileModel = new UserProfileModel();

        $userDatas["idUser"] = $lastUserId;

        return $this->userProfileModel->createNewUserProfile($userDatas);
    }

    private function isUsernameOrMailAvailable($username, $mail)
    {
        // Check if the username and mail are available in the database
        $existingMail = $this->userModel->where('mail', $mail)->first();
        $existingUser = $this->userModel->where('login', $username)->first();
        /*
        Return values:
        0 - Both mail and username are available
        1 - Mail is not available
        2 - Username is not available
        3 - Both mail and username are not available
        */
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