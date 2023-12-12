<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    public $user = [
        'login'      => 'required|min_length[3]|max_length[50]',
        'password'   => 'required|min_length[8]',
        'email'      => 'required|valid_email',
        'birthdate'  => 'valid_date',
        'profilePic' => 'uploaded[profilePic]|max_size[profilePic,1024]|is_image[profilePic]',
    ];

    // Validation rules for the Product controller
    public $product = [
        'name'        => 'required|min_length[3]|max_length[50]',
        'description' => 'max_length[255]',
        'price'       => 'required|numeric',
        'stock'       => 'required|integer',
        'image'       => 'uploaded[image]|max_size[image,2048]|is_image[image]',
    ];

    // Validation rules for the Address controller
    public $address = [
        'number'      => 'required|integer',
        'street'      => 'required|max_length[255]',
        'city'        => 'required|max_length[255]',
        'postalCode'  => 'required|max_length[10]',
        'country'     => 'required|max_length[255]',
        'createdAt'   => 'valid_date',
        'modifiedAt'  => 'valid_date',
        'addressName' => 'max_length[255]',
    ];

    // Validation rules for the Auth controller
    public $auth = [
        'username' => 'required|min_length[3]|max_length[50]',
        'password' => 'required|min_length[8]',
    ];

}
