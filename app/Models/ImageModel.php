<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model
{
    protected $table = 'image';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'weight',
        'path',
        'type',
        'description',
        'name',
        'createdAt',
        'modifiedAt'
    ];
}
