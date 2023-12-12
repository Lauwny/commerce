<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $table = 'user_profile';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'name',
        'firstname',
        'birthdate',
        'sexe',
        'profilePicPath',
        'idAdress',
        'idUser',
        'createdAt',
        'modifiedAt'
    ];

    protected $returnType = 'object';

    // Establishing a relationship with UtilisateurModel
    protected $with = ['linkedUser'];

    // Defining the relationship
    public function linkedUser()
    {
        // Manual relationship using Query Builder
        $builder = $this->db->table('user');
        $builder->where('id', $this->idUser);
        $query = $builder->get();

        return $query->getRow();
    }

    // A method to create a new user profile
    public function createNewUserProfile($userDatas)
    {
        $this->logger("info", "UserProfileModel::createNewUserProfile init");
        return parent::insert($userDatas);
        // Your logic to create a new user profile goes here
        // For example, $this->insert($userDatas);
    }

}

