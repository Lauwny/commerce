<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriesModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'categoryName',
        'isParent',
        'idParent',
        'createdAt',
        'modifiedAt'
    ];
}