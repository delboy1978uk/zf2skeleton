<?php
namespace DelUser;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use Zend\EventManager\Event;
use ZfcUser\Controller\RedirectCallback;
use DelUser\Controller\UserController;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $eventManager        = $app->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sharedManager  = $eventManager->getSharedManager();
        $sharedManager->attach('ZfcUser\Service\User', 'register.post', function (Event $event) use ($e) {

            $user = $event->getParam('user');
            $adapter = $e->getApplication()->getServiceManager()->get('zfcuser_zend_db_adapter');
            $sql = new \Zend\Db\Sql\Sql($adapter);
            $insert = new \Zend\Db\Sql\Insert('user_role_linker');
            $insert->columns(array('user_id', 'role_id'));
            $insert->values(array('user_id' => $user->getId(), 'role_id' => 'user'), $insert::VALUES_MERGE);
            $adapter->query($sql->getSqlStringForSqlObject($insert), $adapter::QUERY_MODE_EXECUTE);

            $url = $e->getRouter()->assemble(array(), array('name' => 'verify-mail-sent'));
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        });

    }



    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'zfcuser' => function($controllerManager) {
                    /* @var ControllerManager $controllerManager*/
                    $serviceManager = $controllerManager->getServiceLocator();

                    /* @var RedirectCallback $redirectCallback */
                    $redirectCallback = $serviceManager->get('zfcuser_redirect_callback');

                    /* @var UserController $controller */
                    $controller = new UserController($redirectCallback);

                    return $controller;
                },
            ),
        );
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'del_user_svc' => 'DelUser\Service\User',
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

        public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
