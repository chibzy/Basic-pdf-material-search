<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Search for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Search\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Search\Model\admin;
use Search\Model\adminTable;
use Search\Model\doc;
use Search\Model\docTable;

class SkeletonController extends AbstractActionController
{
    public function indexAction()
    {
        $request=$this->getRequest();
        if($request->isPost('btnSearch')){
            $item=$request->getPost('search');
            if($item!=''){
                $this->redirect()->toUrl('search?search='.$item);
            }
            
        }
        return array();
    }

    public function searchAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /skeleton/skeleton/foo
        $available=array();
        
        $request=$this->Params(); #fetch parameter from url
        #echo "what ".$request->fromQuery('search');
        
        $text='';
        
        if($request->fromQuery('search')!=null){
            #populate the search table with search result
            #place the searched value on the input field.
            $text=$request->fromQuery('search'); #try and screen content before comparing in database
            
            /*$docs=$this->getDocTable()->fetchDoc($text,true);#fetchall();
            $i=0;
            if(!empty($docs)){
                foreach ($docs as $doc){
                    
                    if(strchr(strtolower($doc->title),strtolower($text)) || strchr(strtolower($doc->author), strtolower($text))){
                        $available[$i]=$doc;
                        
                        $i++;
                    }
                    
                }
            }else{
                return array('alert'=>'Document not found.','searchtext'=>$text);
            }*/
            
            // grab the paginator from the AlbumTable
            $paginator = $this->getDocTable()->fetchDoc($text,true);
            // set the current page to what has been passed in query string, or to 1 if none set
            $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
            // set the number of items per page to 10
            $paginator->setItemCountPerPage(10);
            
            return new ViewModel(array(
                'paginator' => $paginator,
                'searchtext'=>$text
            ));
            
        }else{
            $this->redirect()->toRoute('home');
        }
        return array();
    }
    public function adminAction() {
        if(!isset($_SESSION)){
            session_start();
        }
        $request=$this->getRequest();
        
        $alert='';
        
        if($request->isPost('btnSubmit')){
            
            
            $username=$this->getRequest()->getPost('username');
            $password=$this->getRequest()->getPost('password');
            
            if($username!='' && $password!=''){
                
                $admin=$this->getAdminTable()->fetchall();
                #$admin=$this->($admin);
                
                #$i=0;
                foreach ($admin as $object){
                    if(strtolower($username)==strtolower($object->email) && strtolower($password)==strtolower($object->password)){
                    
                        $_SESSION['adminid']=$object->adminid;
                        #$_SESSION['loggedin']=time();
                        $this->redirect()->toRoute('dashboard');
                    }
                    #$i++;
                }
                $alert='Invalid username or password.';
            }else{
                $alert='Empty identity supplied.';
            }
            
        }
        return array('alert'=>$alert);
    }
    public function getAdminTable(){
        $sm=$this->getServiceLocator();
        $admin=$sm->get('adminTable');
        return $admin;
    }
    public function getDocTable(){
        $sm=$this->getServiceLocator();
        $doc=$sm->get('docTable');
        return $doc;
    }
}
