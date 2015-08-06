<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'del_user_svc' => 'Application\Service\User',
            ],
        ];
    }
}
