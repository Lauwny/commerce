<?php

namespace App\Helpers;

class PasswordApp
{
    static function passwordVerify($password, $hash){
        return hash("sha256", $password) == $hash;
    }
}