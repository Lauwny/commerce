<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;

class AuthController extends BaseController
{
    public $userModel;

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

        // Verify password
        if ($user && $userpassword == $user->password) {
            $session = session();
            $session->set('userId', $user->id);
            $session->set('loggedIn', true);
            $this->logger("info", "session created with s_id ".session_id()." for user $userlogin");
            return redirect()->to('/testlog');
        } else {
            $this->logger("error", "user $userlogin failed to login");
            return redirect()->to('/testlog');
        }
    }


    public function logout()
    {
        $session = session();
        // Check if the user is logged in
        if ($session->get('loggedIn') !== null) {
            // Destroy the session
            $session->destroy();
            // Redirect or display a message as needed
            return redirect()->to('/testlog');
        } else {
            echo "No active session";
        }
    }
}