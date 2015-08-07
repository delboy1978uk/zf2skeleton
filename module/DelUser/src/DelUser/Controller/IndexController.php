<?php
/**
 * User: delboy1978uk
 * Date: 07/08/15
 * Time: 00:39
 */

namespace DelUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User;

class IndexController extends AbstractActionController
{
    public function forgotPasswordAction()
    {
        try {
            /** @var $svc \Application\Service\User */
            $svc = $this->getServiceLocator()->get('del_user_svc');
            $svc->sendPasswordResetEmail($this->params('email'));




            // Get the User
            /** @var $svc \ZfcUser\Service\User */
            $svc = $this->getServiceLocator()->get('zfcuser_user_service');
            $user = $svc->getUserMapper()->findByEmail($email);
            if(!$user){
                $this->getResponse()->setStatusCode(404);
                return true;
            }

            // Mail the User
            /** @var $svc \HtUserRegistration\Service\UserRegistrationService */
            $svc = $this->getServiceLocator()->get('HtUserRegistration\UserRegistrationService');
            $svc->sendPasswordRequestEmail($user);
        } catch(Exception $e) {

        }

        return new ViewModel();
    }
}