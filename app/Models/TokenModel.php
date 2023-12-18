<?php


namespace App\Models;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class TokenModel extends Model {

    protected $table = 'token';
    protected $primaryKey = 'tokenId';

    protected $allowedFields = ['tokenValue', 'userId'];

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }

    public function insertRevokedToken($tokenValue, $userId){
        $this->logger("info", "TokenModel::insertRevokedToken init. With value : $tokenValue");
        return parent::insert(array("tokenValue"=>$tokenValue, "userId"=>$userId), false);
    }
}
