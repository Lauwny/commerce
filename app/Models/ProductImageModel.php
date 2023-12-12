<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table = 'productimage';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'idImage',
        'idProduct',
        'isMainImage',
        'createdAt',
        'modifiedAt'
    ];
}
