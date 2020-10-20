<?php 
namespace search\model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\DB\Adapter\Adapter;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class docTable{
    var $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway=$tableGateway;
    }
    public function fetchAll($paginated=false){
        $resultSet=$this->tableGateway->select();
        
        if ($paginated) {
            // create a new Select object for the table album
            #$select = new Select('doc');
            $select = new Select();
            $select->from('doc');
            #$select->where("locate ('$text',author) or locate('$text',title)"); #sql synthax work well
            // create a new result set based on the Doc entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Doc());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
                // our configured select object
                $select,
                // the adapter to run it against
                $this->tableGateway->getAdapter(),
                // the result set to hydrate
                $resultSetPrototype
                );
            $paginator = new Paginator($paginatorAdapter);
            
            return $paginator;
        }
        
        return $resultSet;
    }
    public function fetchDoc($text,$paginated=false){
        if(isset($text)){
            
            if ($paginated) {
                // create a new Select object for the table album
                #$select = new Select('doc');
                $select = new Select();
                    $select->from('doc');
                    $select->where("locate ('$text',author) or locate('$text',title)"); #sql synthax work well
                // create a new result set based on the Doc entity
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Doc());
                // create a new pagination adapter object
                $paginatorAdapter = new DbSelect(
                // our configured select object
                    $select,
                    // the adapter to run it against
                    $this->tableGateway->getAdapter(),
                    // the result set to hydrate
                    $resultSetPrototype
                    );
                $paginator = new Paginator($paginatorAdapter);
                
                #echo $select->getSqlString($this->dbAdapter)." here ||"; # HELPED ME IN SEE THE MY SQL QUERY AS I TRY THEM.
                return $paginator;
            }
            
            #$resultSet=$this->tableGateway->select()->where()->locate(array('title'=>$text));
            $resultSet=$this->tableGateway->select(function(Select $select){
                  #$select->where('locate (?),title','$text');
                  $select->where('locate (title,title)');
                         #->orwhere('locate (author,title)');
                  #$select->order('title ASC');
                  
            });
            
            #echo $this->tableGateway->select()->getSqlString()." - Wow!";
            
            #$row=$rowset->current();
            if(empty($resultSet)){
                throw new \Exception('Could not find document');
            }
            return $resultSet;
        }
    }
    
    public function fetchAllWithTitleSince($text)
    {
        #$dbAdapter=new \Zend\Db\Adapter\Adapter('db');
        $sql = new Sql($this->dbAdapter);
        
        $select = $sql->select();
        $select->from('doc');
        #$select->columns(array('id', 'title', 'url', 'date_updated'));
        #$select->where->like('title', "%$title%");
        #$select->where->greaterThanOrEqualTo('date_created', date('Y-m-d', strtotime($since)));
        
        $select->where('locate (?),title','$text');
        
        $statement = $this->dbAdapter->createStatement();
        $select->prepareStatement($this->dbAdapter, $statement);
        
        $statement->execute();
        
        return $select->getSqlString($this->dbAdapter->getPlatform());
    }
    
    public function getDoc($id){
        if($id){
            $rowset=$this->tableGateway->select(array('sn'=>$id));
            $row=$rowset->current();
            if(!$row){
                throw new \Exception ("Could not find row");
            }
            return $row;
        }
    }
    public function saveDoc(doc $doc){
        $data=array(
            'title'=>$doc->title,
            'note'=>$doc->note,
            'pagenumber'=>$doc->pagenumber,
            'filetype'=>$doc->filetype,
            'regdate'=>$doc->regdate,
            'lastview'=>$doc->lastview,
            'filepath'=>$doc->filepath,
            'genre'=>$doc->genre,
            'author'=>$doc->author,
            'publisher'=>$doc->publisher
        );
        $id=(int)$doc->sn;
        if($id==0){
            $this->tableGateway->insert($data);
        }else{
            if($this->getDoc($id)){
                if($doc->filepath==''){
                    $Mdata=array(
                        'title'=>$doc->title,
                        'note'=>$doc->note,
                        'pagenumber'=>$doc->pagenumber,
                        'filetype'=>$doc->filetype,
                        'regdate'=>$doc->regdate,
                        'lastview'=>$doc->lastview,
                        'genre'=>$doc->genre,
                        'author'=>$doc->author,
                        'publisher'=>$doc->publisher
                    );
                    $this->tableGateway->update($Mdata,array('sn'=>$id));
                }else{
                    $v=$this->tableGateway->update($data,array('sn'=>$id));
                    
                }
            }else{
                throw new \Exception ('Document ID does not exist');
            }
        }
    }
    public function deleteDoc($id){
       $this->tableGateway->delete(array('sn'=>(int)$id));
        
    }
    public function uploadDocFile($logo,$newFileName){
        $msg="";
        $result=array();
        if($logo['name']!='' && $logo['size']<=2000000){
            
            $ext=explode('.',$logo['name']);
            if($ext[1]=='pdf' || $ext[1]=='doc' || $ext[1]=='docx' || $ext[1]=='xls' || $ext[1]=='xlsx'){#restrict to *.jpeg file format
                #echo "{$logo['tmp_name']} - <br>";
                if($logo['tmp_name']!=''){
                    
                    #$dest="books/$newFileName.{$ext[1]}"; #change to during "images/$dir/$newFileName.{$ext[1]}" coupling
                    $dest="books/$newFileName.{$ext[1]}";
                    move_uploaded_file($logo['tmp_name'],"./public/$dest");
                    $result[0]=$dest;
                    return $result;
                }
                
            }else{
                $msg='<p class="alert alert-danger">The document file format is not supported</p>';
            }
        }else{
            #echo 'too big image';
            $msg='<p class="alert alert-danger">There is no selected image file or the selected file size is bigger than 2Mb</p>'; #Error generation for empty passport is not required
        }
        return $msg;
    }
    public function removeFile($dir){
        $msg="";
        
        if($dir!=""){
            $old=getcwd();#get the current directory to understand the directory u re operating from. in this case it warranted me to add "../" that is wy it worked.
            
            @unlink('./public/'.$dir); #erases the content in the file directory.
            chdir($old);
        }
        $msg="removed ".$dir;
        return $msg;
    }
}