<?php
namespace search\model;

class doc{
    var $sn;
    var $title;
    var $pagenumber;
    var $note;
    var $filetype;
    var $regdate;
    var $lastview;
    var $filepath;
    var $genre;
    var $author;
    var $publisher;
    
    public function exchangeArray($data){
        if($data['sn']!=''){
            $this->sn=$data['sn'];
        }else{
            $this->sn=null;
        }
        if($data['title']!=''){
            $this->title=$data['title'];
        }else{
            $this->title=null;
        }
        if($data['pagenumber']!=''){
            $this->pagenumber=$data['pagenumber'];
        }else{
            $this->pagenumber=null;
        }
        if($data['note']!=''){
            $this->note=$data['note'];
        }else{
            $this->note=null;
        }
        if($data['filetype']!=''){
            $this->filetype=$data['filetype'];
        }else{
            $this->filetype=null;
        }
        if($data['regdate']!=''){
            $this->regdate=$data['regdate'];
        }else{
            $this->regdate=null;
        }
        if($data['lastview']!=''){
            $this->lastview=$data['lastview'];
        }else{
            $this->lastview=null;
        }
        if($data['filepath']!=''){
            $this->filepath=$data['filepath'];
        }else{
            $this->filepath=null;
        }
        if($data['genre']!=''){
            $this->genre=$data['genre'];
        }else{
            $this->genre=null;
        }
        if($data['author']!=''){
            $this->author=$data['author'];
        }else{
            $this->author;
        }
        if($data['publisher']!=''){
            $this->publisher=$data['publisher'];
        }else{
            $this->publisher=null;
        }
    }
}