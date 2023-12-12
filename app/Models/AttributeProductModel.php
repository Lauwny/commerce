<?php

namespace App\Models;

use CodeIgniter\Model;

class AttributeProductModel extends Model
{
    protected $table = 'attributeproduct';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'idAttribute',
        'idProduct'
    ];
}