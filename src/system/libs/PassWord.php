<?php
namespace yellowheroes\bugs\system\libs;

use yellowheroes\bugs\system\config as config;

class PassWord
{

    function __construct()
    {
        
    }

    public function hashPassWord($passWord)
    {
        $passWordHash = \password_hash($passWord, \PASSWORD_DEFAULT);
        return $passWordHash;
    }

    public function verifyPassWord($passWord, $passWordHash)
    {
        $verify = \password_verify($passWord, $passWordHash);
        return $verify;
    }
}
