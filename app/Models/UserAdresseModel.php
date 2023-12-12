<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAdresseModel extends Model
{
    protected $table = 'useradresse';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'idUser',
        'idAdresse',
        'adresse_name'
    ];

    protected $returnType = 'object';

    protected $with = ['adresse'];

    public function adresse()
    {
        return $this->belongsTo(AdresseModel::class, 'idAdresse', 'id');
    }
}
