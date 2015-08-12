<?php

namespace DelUser\Service;

use Exception;
use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\EmailAddress;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Service\User as UserSvc;
use HtUserRegistration\Mapper\UserRegistrationMapperInterface;
use DateTime;
use MtMail\Service\Mail;
use ZfcUser\Entity\User as ZfcUser;

class User
{
    /** @var ServiceLocatorInterface */
    protected $sl;

    /** @var $svc UserSvc */
    protected $zfc_user_svc;

    /** @var $mapper UserRegistrationMapperInterface */
    protected $reg_mapper;

    /** @var $mailer Mail */
    protected $mailer;

    public function sendPasswordResetEmail($email,ServiceLocatorInterface $sl)
    {
        $this->sl = $sl;

        if(!$email){
            throw new Exception('No Email passed');
        }

        $validator = new EmailAddress();
        if(!$validator->isValid($email)){
            throw new Exception('This is not an email address!');
        }

        $svc = $this->getZfcUserSvc();
        /** @var $user ZfcUser */
        $user = $svc->getUserMapper()->findByEmail($email);
        if(!$user){
            throw new Exception('No user found');
        }

        $time = new DateTime();
        /** @var $mapper UserRegistrationMapperInterface */
        $mapper = $this->getRegMapper();
        $registration = $mapper->findByUser($user);
        $registration->generateToken();
        $registration->setRequestTime($time);
        $mapper->update($registration);

        $token = $registration->getToken();

        /** @var $mailer Mail */
        $mailer = $this->getMailer();
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

    public function resetPassword($id,$form,ServiceLocatorInterface $sl)
    {
        $this->sl = $sl;
        /** @var $user ZfcUser */
        $user = $this->getZfcUserSvc()->getUserMapper()->findById($id);
        if(!$user)
        {
            throw new Exception('No user found with that ID.');
        }
        $bcrypt = new Bcrypt();
        $bcrypt->setCost(14);
        $new = $form->get('newCredential')->getValue();
        $password = $bcrypt->create($new);
        $user->setPassword($password);
        $this->getZfcUserSvc()->getUserMapper()->update($user);
    }

    /**
     * @param $id
     * @param $token
     * @param ServiceLocatorInterface $sl
     * @return bool
     * @throws \Exception
     */
    public function tokenMatches($id,$token,ServiceLocatorInterface $sl)
    {
        $this->sl = $sl;
        $user = $this->getZfcUserSvc()->getUserMapper()->findById($id);
        if(!$user)
        {
            throw new Exception('No user found with that ID.');
        }
        $reg = $this->getRegMapper()->findByUser($user);
        if($reg->getToken() !== $token)
        {
            return false;
        }
        return true;
    }

    /**
     * @return \MtMail\Service\Mail
     */
    private function getMailer()
    {
        if(!$this->mailer)
        {
            $this->mailer = $this->sl->get('MtMail\Service\Mail');
        }
        return $this->mailer;
    }


    /**
     * @return array|object|UserSvc
     */
    private function getZfcUserSvc()
    {
        if(!$this->zfc_user_svc)
        {
            $this->zfc_user_svc = $this->sl->get('zfcuser_user_service');
        }
        return $this->zfc_user_svc;
    }


    /**
     * @return array|UserRegistrationMapperInterface|object
     */
    private function getRegMapper()
    {
        if(!$this->reg_mapper)
        {
            $this->reg_mapper = $this->sl->get('HtUserRegistration\UserRegistrationMapper');
        }
        return $this->reg_mapper;
    }

    /**
     * @param int $id
     * @param ServiceLocatorInterface $sl
     * @return array
     */
    public function resendActivationEmail($id,ServiceLocatorInterface $sl)
    {
        $this->sl = $sl;
        $user = $this->getZfcUserSvc()->getUserMapper()->findById($id);
        /** @var \HtUserRegistration\Entity\UserRegistration $reg */
        $reg = $this->getRegMapper()->findByUser($user);

        $reg->setRequestTime(new DateTime('+ 1 day'));
        $reg->setResponded(false);
        $this->getRegMapper()->update($reg);

        /** @var $svc \HtUserRegistration\Mailer\Mailer */
        $svc = $sl->get('HtUserRegistration\Mailer\Mailer');
        $svc->sendVerificationEmail($reg);
        
        return [
            'message' => 'Account activation email sent',
            'class' => 'success',
        ];
    }
}