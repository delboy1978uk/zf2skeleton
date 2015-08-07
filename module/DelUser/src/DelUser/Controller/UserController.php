<?php
/**
 * User: delboy1978uk
 * Date: 07/08/15
 * Time: 00:39
 */

namespace DelUser\Controller;

use Zend\View\Model\ViewModel;
use ZfcUser\Service\User;
use ZfcUser\Controller\UserController as ZfcUserController;

class UserController extends ZfcUserController
{
    public function forgotPasswordAction()
    {
        $email = $this->params()->fromRoute('email');
        /** @var $svc \DelUser\Service\User */
        $svc = $this->getServiceLocator()->get('del_user_svc');
        $svc->sendPasswordResetEmail($email, $this->getServiceLocator());

        return new ViewModel([
            'email' => $email,
        ]);
    }

    public function resetPasswordAction()
    {
        $id = $this->params()->fromRoute('id');
        $token = $this->params()->fromRoute('token');

        return new ViewModel([
            'id' => $id,
            'token' => $token,
        ]);
    }




    /**
     * Login form
     */
    public function loginAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $request = $this->getRequest();
        $form    = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        if (!$request->isPost()) {
            $msg = $this->flashMessenger()->getMessages('deluser-entered-email');
            $email = (count($msg) > 0) ? $msg[0] : null;
            return array(
                'loginForm' => $form,
                'redirect'  => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'email' => $email,
            );
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN).($redirect ? '?redirect='. rawurlencode($redirect) : ''));
        }

        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }






    /**
     * General-purpose authentication action
     */
    public function authenticateAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        $this->flashMessenger()->setNamespace('deluser-entered-email')->addMessage($this->params()->fromPost('identity'));

        $result = $adapter->prepareForAuthentication($this->getRequest());

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

        if (!$auth->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            $adapter->resetAdapters();
            return $this->redirect()->toUrl(
                $this->url()->fromRoute(static::ROUTE_LOGIN) .
                    ($redirect ? '?redirect='. rawurlencode($redirect) : '')
            );
        }

        $redirect = $this->redirectCallback;

        return $redirect();
    }


}