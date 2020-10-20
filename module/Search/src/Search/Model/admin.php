<?php 
namespace Search\Model;

class admin{
    
    var $sn;
    var $fanme;
    var $sname;
    var $password;
    var $phone;
    var $email;
    var $adminid;
    var $activation_status;
    var $passport;
    
    public function exchangeArray($data){
        if($data['sn']!=''){
            $this->sn=$data['sn'];
        }else $this->sn=null;
        if($data['fname']!=''){
            $this->fname=$data['fname'];
        }else $this->fname=null;
        if($data['sname']!=''){
            $this->sname=$data['sname'];
        }else $this->sname=null;
        if($data['password']!=''){
            $this->password=$data['password'];
        }else $this->password=null;
        if($data['phone']!=''){
            $this->phone=$data['phone'];
        }else $this->phone=null;
        if($data['email']!=''){
            $this->email=$data['email'];
        }else $this->email=null;
        if($data['adminid']!=''){
            $this->adminid=$data['adminid'];
        }else $this->adminid=null;
        if($data['activation_status']!=''){
            $this->activation_status=$data['activation_status'];
        }else $this->activation_status=null;
        if($data['passport']!=''){
            $this->passport=$data['passport'];
        }else $this->passport=null;
    }
    
}