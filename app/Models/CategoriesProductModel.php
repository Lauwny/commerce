<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriesProductModel extends Model
{
    protected $table = 'categoriesproduct';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'idCategory',
        'idProduct'
    ];
}
