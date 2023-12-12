<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductFileModel extends Model
{
    protected $table = 'productfile';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'name',
        'description',
        'weight',
        'length',
        'width',
        'height',
        'stock',
        'createdAt',
        'modifiedAt'
    ];
}
