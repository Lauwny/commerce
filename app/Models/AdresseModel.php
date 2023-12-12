<?php

namespace App\Models;

use CodeIgniter\Model;

class AdresseModel extends Model
{
    protected $table = 'adresse';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'number',
        'street',
        'city',
        'postalCode',
        'country',
        'createdAt',
        'modifiedAt',
        'adresse_name'
    ];
}