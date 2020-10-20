<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Search\Controller\Skeleton' => 'Search\Controller\SkeletonController',
            'Search\Controller\Admin'=>'Search\Controller\AdminController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Search\Controller',
                        'controller'    => 'Skeleton',
                        'action'        => 'index',
                    ),
                ),
            ),
            'search' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/search',
                    'defaults' => array(
                        'controller'=>'Search\Controller\Skeleton',
                        'action'     => 'search',
                    ),
                ),
            ),
            'admin'=>array(
                'type'=>'Literal',
                'options'=>array(
                    'route'=>'/admin',
                    'defaults'=>array(
                        'controller'=>'Search\Controller\Skeleton',
                        'action'=>'admin'
                    ),
                ),
            ),
            'dashboard'=>array(
                'type'=>'Literal',
                'options'=>array(
                    'route'=>'/dashboard',
                    'defaults'=>array(
                        'controller'=>"Search\Controller\Admin",
                        'action'=>'dashboard'
                    ),
                ),
            ),
            'addnew'=>array(
                'type'=>'Literal',
                'options'=>array(
                    'route'=>'/addnew',
                    'defaults'=>array(
                          'controller'=>'Search\Controller\Admin',
                          'action'=>'addnew'
                    ),
                ),
            ),
            'edit'=>array(
                'type'=>'Literal',
                'options'=>array(
                    'route'=>'/edit',
                    'defaults'=>array(
                        'controller'=>'Search\Controller\Admin',
                        'action'=>'edit'
                    ),
                ),
            ),
            'signout'=>array(
                'type'=>'Literal',
                'options'=>array(
                    'route'=>'/signout',
                    'defaults'=>array(
                        'controller'=>'Search\Controller\Admin',
                        'action'=>'signout'
                    ),
                ),
            ),
            'deleteDoc'=>array(
                'type'=>'Literal',
                'options'=>array(
                    'route'=>'/delete',
                    'defaults'=>array(
                        'controller'=>'Search\Controller\Admin',
                        'action'=>'deleteDoc'
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/admin'            =>__DIR__.'/../view/layout/adminlayout.phtml',
            'search/index/index' => __DIR__ . '/../view/search/skeleton/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'search/skeleton/admin'   =>__DIR__.'/../view/admin/admin.phtml',
            'search/admin/dashboard'  =>__DIR__.'/../view/admin/dashboard.phtml',
            'search/admin/addnew'     =>__DIR__.'/../view/admin/addnew.phtml',
            'search/admin/edit'       =>__DIR__.'/../view/admin/edit.phtml'
        ),
        'template_path_stack' => array(
            'Search' => __DIR__ . '/../view',
        ),
        'strategies'=>array(
            'ViewJsonStrategy',
        ),
    ),
);
