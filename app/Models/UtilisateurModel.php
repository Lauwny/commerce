<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'login',
        'password',
        'mail',
        'createdAt',
        'modifiedAt',
        'lastLogin'
    ];

    protected $returnType = 'object';

    public function createNewUser($userDatas){
        $this->logger("info", "UtilisateurModel::createNewUser init");
    }
}
