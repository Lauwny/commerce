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

    public function setLastLoginDate($userId, $userDatas){
        if($userId != null) {
            $this->logger("info", "UtilisateurModel::setLastLoginDate init with data : ".$userDatas['lastLogin'] . "for id : $userId");
            $settingLastLogin = $this->where('id', $userId)->update($id=null, $userDatas);
            if ($settingLastLogin) {
                $this->logger("info", "lastLogin successfully updated for user with id : $userId");
            } else {
                $this->logger("error", "lastLogin error during update for user with id : $userId");
            }
        }else{
            $this->logger("error", "lastLogin error during update for user. userId var is null");
        }
    }
    public function createNewUser($userDatas){
        $this->logger("info", "UtilisateurModel::createNewUser init");
        return parent::insert($userDatas, false);
    }
}
