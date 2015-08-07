<?php

namespace DelUser\Service;

use Exception;
use Zend\Validator\EmailAddress;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Service\User as UserSvc;
use HtUserRegistration\Mapper\UserRegistrationMapperInterface;
use DateTime;
use MtMail\Service\Mail;
use ZfcUser\Entity\User as ZfcUser;

class User
{
    public function sendPasswordResetEmail($email,ServiceLocatorInterface $sl)
    {
        if(!$email){
            throw new Exception('No Email passed');
        }

        $validator = new EmailAddress();
        if(!$validator->isValid($email)){
            throw new Exception('This is not an email address!');
        }

        /** @var $svc UserSvc */
        $svc = $sl->get('zfcuser_user_service');
        /** @var $user ZfcUser */
        $user = $svc->getUserMapper()->findByEmail($email);
        if(!$user){
            throw new Exception('No user found');
        }

        $time = new DateTime();
        /** @var $mapper UserRegistrationMapperInterface */
        $mapper = $sl->get('HtUserRegistration\UserRegistrationMapper');
        $registration = $mapper->findByUser($user);
        $registration->generateToken();
        $registration->setRequestTime($time);
        $mapper->update($registration);

        $token = $registration->getToken();

        /** @var $mailer Mail */
        $mailer = $sl->get('MtMail\Service\Mail');
        $message = $mailer->compose([
            'to' => $email,
        ],
        'del-user/mail/reset-password',[
            'id' => $user->getId(),
            'token' => $token,
            'time' => $time
        ]);
        $message->setFrom('noreply@zf2skeletoncrew.com');
        $message->setSubject('Reset your password');
        $mailer->send($message);

    }
}