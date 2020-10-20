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
use Search\Model\docTable;
use Search\Model\doc;
use Zend\Config\Reader\Json;
use Zend\View\Model\JsonModel;


class AdminController extends AbstractActionController
{
    public function dashboardAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /skeleton/skeleton/foo
        $this->layout()->setTemplate('layout/admin');
        $this->validateAdminProtectedPageStatus();
        $request=$this->getRequest();
        
        $result=array();
        $item=$this->params()->fromQuery('search');
        
        if($request->isPost('searchDoc')){
            $item=$request->getPost('search');#screen content before usage 
            if(isset($item)){
                #echo "Next <br>";
               $result=$this->getdocTable()->fetchDoc($item,true); 
                
            }
            
        }elseif(!empty($this->params()->fromQuery('search'))){
            if(isset($item)){
                $result=$this->getdocTable()->fetchDoc($item,true);
                
            }
        }else{
            $result=$this->getDocTable()->fetchall(true);
        }
        $result->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $result->setItemCountPerPage(10);
        
        return new ViewModel(array(
                'result'=> $result,
                'item'=> $item
            ));
    }
    public function addnewAction() {
        $this->layout()->setTemplate('layout/admin');
        $this->validateAdminProtectedPageStatus();
        $request=$this->getRequest();
        
        if($request->isPost('btnAdd')){
            #validate the inputs
            #add content to table
            #return empty variable and success alert
            $title=$this->params()->fromPost('title');
            $note=$this->params()->fromPost('note');
            $genre=$this->params()->fromPost('genre');
            $filetype=$this->params()->fromPost('filetype');
            $pagenumber=$this->params()->fromPost('pagenumber');
            $author=$this->params()->fromPost('author');
            $publisher=$this->params()->fromPost('publisher');
            $doc=$request->getFiles('doc');
            
            if(isset($title) && isset($note) && isset($author) && isset($publisher) && isset($doc)){
                
                $Material=new doc();
                
                #upload document
                $filepath=$this->getDocTable()->uploadDocFile($doc,$title);
                #echo $filepath[0]." - path";
                #prepare submittable data
                $regdate=date('Y-m-d');
                $lastviewed=$regdate;
                $post=array(
                    'title'=>$title,
                    'pagenumber'=>$pagenumber,
                    'note'=>$note,
                    'filetype'=>$filetype,
                    'regdate'=>$regdate,
                    'filepath'=>$filepath[0],
                    'genre'=>$genre,
                    'author'=>$author,
                    'publisher'=>$publisher,
                    'lastview'=>$lastviewed,
                    'sn'=>''
                );
                
                $Material->exchangeArray($post);#changes the content of those array to object
                
                $this->getDocTable()->saveDoc($Material);
                return new viewModel(array(
                    'alert'=>"<div class='alert alert-success'>Document saved successfully.</div>"
                    ));
            }else{
                
                return new ViewModel(array(
                    'title'=>$title,
                    'note'=>$note,
                    'genre'=>$genre,
                    'filetype'=>$filetype,
                    'pagenumber'=>$pagenumber,
                    'author'=>$author,
                    'publisher'=>$publisher,
                    'doc'=>$doc,
                    'alert'=>"<div class='alert alert-danger'>One or two required filed is not set.</div>"
                ));
            }
        }
        return array();
    }
    public function validateAdminProtectedPageStatus(){
        if(!isset($_SESSION)){
            session_start();
        }
        if (isset($_SESSION['loggedin']) && (time() - $_SESSION['loggedin'] > 1800)) {
            // last request was more than 5 minutes ago
            $this->redirect()->toRoute('signout');
        }
        $_SESSION['loggedin'] = time(); // update last activity time stamp
        
        if(!isset($_SESSION['adminid'])){
            $this->redirect()->toRoute('signout');
        }
    }
    public function editAction(){
        $this->layout()->setTemplate('layout/admin');
        
        $this->validateAdminProtectedPageStatus();
        $req=$this->getRequest();
        $request=$this->params();
        $id=(int)$request->fromQuery('id');
        
        #remember to clean the data fetched from url.
        $detail=$this->getDocTable()->getDoc($id);
        
        
        if($req->isPost('btnupdate')){
            #validate the inputs
            #add content to table
            #return empty variable and success alert
            $title=$this->params()->fromPost('title');
            $note=$this->params()->fromPost('note');
            $genre=$this->params()->fromPost('genre');
            $filetype=$this->params()->fromPost('filetype');
            $pagenumber=$this->params()->fromPost('pagenumber');
            $author=$this->params()->fromPost('author');
            $publisher=$this->params()->fromPost('publisher');
            $doc=$req->getFiles('doc');
            
            if(isset($title) && isset($note) && isset($author) && isset($publisher)){
                
                $Material=new doc();
                #var_dump($doc); #very essensital for determin the content of any variable including objects
                if($doc['name']!=''){
                #upload document
                   # echo "Path present";
                    $filepath=$this->getDocTable()->uploadDocFile($doc,$title);
                }else{
                   # echo "Path not present";
                    $filepath=array('');
                }
                #echo $filepath[0]." - path";
                #prepare submittable data
                $regdate=date('Y-m-d');
                $lastviewed=$regdate;
                $post=array(
                    'title'=>$title,
                    'pagenumber'=>$pagenumber,
                    'note'=>$note,
                    'filetype'=>$filetype,
                    'regdate'=>'',#$regdate,
                    'filepath'=>$filepath[0],
                    'genre'=>$genre,
                    'author'=>$author,
                    'publisher'=>$publisher,
                    'lastview'=>$lastviewed,
                    'sn'=>$id
                );
                
                $Material->exchangeArray($post);#changes the content of those array to object
                
                $this->getDocTable()->saveDoc($Material);
                return new viewModel(array(
                    'alert'=>"<div class='alert alert-success'>Document updated successfully.</div>",
                    'title'=>$title,
                    'pagenumber'=>$pagenumber,
                    'note'=>$note,
                    'filetype'=>$filetype,
                    'regdate'=>'',#$regdate,
                    #'filepath'=>$filepath[0],
                    'genre'=>$genre,
                    'author'=>$author,
                    'publisher'=>$publisher
                    #'lastview'=>$lastviewed,
                ));
            }else{
                
                return new ViewModel(array(
                    'title'=>$title,
                    'note'=>$note,
                    'genre'=>$genre,
                    'filetype'=>$filetype,
                    'pagenumber'=>$pagenumber,
                    'author'=>$author,
                    'publisher'=>$publisher,
                    'doc'=>$doc,
                    'alert'=>"<div class='alert alert-danger'>One or two required filed is not set.</div>"
                ));
            }
        }
        
        
        return new ViewModel(array(
            'title'=>$detail->title,
            'note'=>$detail->note,
            'genre'=>$detail->genre,
            'filetype'=>$detail->filetype,
            'pagenumber'=>$detail->pagenumber,
            'author'=>$detail->author,
            'publisher'=>$detail->publisher,
            'doc'=>''
            
        ));
    }
    public function deleteDocAction(){
        #$ids=$this->getRequest()->getPost('sn');
        #$ids=$ids[];
        $ids=$this->params()->fromQuery('sn');
        #echo $ids." -wow!\n";
        $val=explode('|', $ids);
        
        #since 10 is the maximum displayable doc
        $i=0;
        while($i<count($val)){
            #echo count($val)." {$val[$i]} wow!\n";
            $doc=$this->getDocTable()->getDoc($val[$i]);
            #remove the file associated with the document.
            
            $output=$this->getDocTable()->removeFile($doc->filepath);
            #delete the affected item on the table
            $this->getDocTable()->deleteDoc($val[$i]);
            $i++;
        }
        
        $data=new JsonModel(array(
            'output'=>'Successful '.$output
        ));
        return $data;
    }
    public function signoutAction(){
        if(!isset($_SESSION)){
            session_start();
        }
        session_unset();
        session_destroy();
        return $this->redirect()->toRoute('home');
    }
    public function getDocTable(){
        $sm=$this->getServiceLocator();
        $doc=$sm->get('docTable');
        return $doc;
    }
    public function getDoc(){
        if(!$this->doc){
            $sm=$this->getServiceLocator();
            $this->doc=$sm->get('Search\Model\doc');
        }
        return $this->doc;
    }
}
