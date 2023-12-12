<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;

class UserController extends BaseController
{
    public $userModel;
    public function __construct()
    {
        $this->logger('info', 'UserController initialized');
        $this->userModel = new UtilisateurModel();
    }

    public function index(): string
    {
        return view('welcome_message');
    }

}