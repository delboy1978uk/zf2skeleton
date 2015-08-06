<?php

namespace Application\Service;

use Exception;

class User
{
    public function sendPasswordResetEmail($email,$sl)
    {
        if(!$email){
            throw new Exception('No Email passed');
        }
    }
}