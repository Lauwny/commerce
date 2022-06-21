<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class TestController extends ResourceController
{
    public function getOneUser($id){
        $userModel = new UserModel();
        $response = $userModel->getOneUserById($id);
        unset($response['userId']);
        unset($response['password']);
        unset($response['created_at']);
        unset($response['updated_at']);
        unset($response['lasted_visited_at']);
        //print_r($response);
        return $this->respondCreated($response);
    }
}