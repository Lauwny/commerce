<?php

namespace App\Controllers\api\v1;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;


class User extends ResourceController
{

    public function index(){
        echo "hello";
    }

    /** User registration : require mail and password **/
    public function register()
    {
        $rules = [
            "mail" => "required|is_unique[user.mailUser]|min_length[4]",
            "password" => "required|min_length[8]"
        ];

        $messages = [

            "mail" => [
                "required" => "mail required",
            ],
            "password" => [
                "required" => "password is required"
            ],
        ];

        if(!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];


        } else {

            $userModel = new UserModel();

            $data = [
                "login" => $this->request->getVar("mail"),
                "mail" => $this->request->getVar("mail"),
                "password" => password_hash($this->request->getVar("password"), "sha256")
            ];



            if ($userModel->insert($data)) {
                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'Successfully, user has been registered',
                    'data' => []
                ];
            } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to create user',
                    'data' => []
                ];
            }
        }
        return $this->respondCreated($response);
    }

    /** Login : verify the information and return  generated token in case of good credential. Else it return 500 error with associated message **/
    public function login()
    {
        $rules = [
            "mail" => "required|min_length[4]",
            "password" => "required|min_length[8]",
        ];

        $messages = [
            "mail" => [
                "required" => "mail required"
            ],
            "password" => [
                "required" => "password is required"
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
            return $this->respondCreated($response);
        } else {
            $userModel = new UserModel();
            $userDatas = $userModel->login($this->request->getVar("mail"), $this->request->getVar("password"));
            $sess = \Config\Services::session();
            $sess->set($userDatas['data']);
            return $this->respondCreated($userDatas);
        }
    }


    /** logout : unset session informations and logout user **/

    public function logout(){

    }

    /** getProfile : return current user profile **/
    public function getProfile($id){
        $userModel = new UserModel();
        $response = $userModel->getOneUserById($id);
        unset($response['userId']);
        unset($response['password']);
        unset($response['created_at']);
        unset($response['updated_at']);
        unset($response['lasted_visited_at']);
        return $this->respondCreated($response);
    }

}
