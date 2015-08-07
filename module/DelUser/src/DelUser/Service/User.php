<?php

namespace DelUser\Service;

use Exception;

class User
{
    public function sendPasswordResetEmail($email)
    {
        if(!$email){
            throw new Exception('No Email passed');
        }
    }
}