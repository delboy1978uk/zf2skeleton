<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

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
