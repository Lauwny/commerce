<?php

namespace App\Models;


use App\Helpers\KeyValidator;
use CodeIgniter\Model;
use App\Helpers\PasswordApp;

class UserModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'userProfile';
    protected $primaryKey = 'userId';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "mail",
        "login",
        "password"
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function login($mail, $password)
    {
        $userdata = $this->where("userMail", $mail)->first();
        if (!empty($userdata)) {
            $userPassword = $password;
            if (PasswordApp::passwordVerify($userPassword, $userdata['userPassword'])) {
                $keyvalidator = new KeyValidator();
                $token = $keyvalidator->generateKey($userdata);
                $response = [
                    'status' => 200,
                    'error' => false,
                    'messages' => 'User logged In successfully',
                    'data' => [
                        'userId' => $userdata['userId'],
                        'token' => $token
                    ]
                ];
            } else {
                $response = [
                    'status' => 500,
                    'error' => true,
                    'messages' => 'Wrong email or password',
                    'data' => []
                ];
            }
            return $response;
        } else {
            $response = [
                'status' => 500,
                'error' => true,
                'messages' => 'Wrong email or password',
                'data' => []
            ];
            return $response;
        }
    }


    public function getOneUserById($id)
    {
        $user = $this->find($id);
        return $user;
    }
}
