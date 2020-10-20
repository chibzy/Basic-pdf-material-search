<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Search for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Search;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Code\Generator\DocBlockGenerator;
use Search\Model\admin;
use Search\Model\adminTable;
use Search\Model\doc;
use Search\Model\docTable;


class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    public function getServiceConfig(){
        return array(
             'factories'=>array(
               'user'=>function($sm){
                   return new user();
               },
               'admin'=>function($sm){
                    return new admin();  
               },
               'doc'=>function($sm){
                    return $doc;  
               },
               'adminTable'=>function($sm){
                     $tableGateway=$sm->get('adminTableGateway');
                     $table=new adminTable($tableGateway);
                     return $table;
               },
               'docTable'=>function($sm){
                    $tableGateway=$sm->get('docTableGateway');
                    $table=new docTable($tableGateway);
                    return $table;
               },
               'adminTableGateway'=>function($sm){
                 $dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
                 $resultsetPrototype=new ResultSet();
                 $resultsetPrototype->setArrayObjectPrototype(new admin());
                 return new TableGateway('admin_info', $dbAdapter,null,$resultsetPrototype);
               },
               'docTableGateway'=>function($sm){
                   $dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
                   $resultsetPrototype=new ResultSet();
                   $resultsetPrototype->setArrayObjectPrototype(new doc);
                   return new TableGateway('doc', $dbAdapter, null, $resultsetPrototype);
               },
             ),
             
        );
    }
}
