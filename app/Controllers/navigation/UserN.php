<?php

namespace App\Controllers\navigation;

use App\Controllers\BaseController;

class UserN extends BaseController
{
    function index(){
        return view('BaseView', array('view'=>'User/index.php'));
    }
    function login(){
        return view('BaseView', array('view'=>'User/login.php'));
    }
    function register(){
        return view('BaseView', array('view'=>'User/register.php'));
    }
    function logout(){
        return view('BaseView', array('view'=>'User/logout.php'));
    }
}