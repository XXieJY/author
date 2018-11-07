<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Request;
class Book extends Controller{
     public function getTitle(){
         $bookid =input('post.bookid');
         $volume_id=input('post.volume_id');
       $content= Db::name('Content')->where(['book_id'=>$bookid,'volume_id'=>$volume_id,'type'=>1])->field('title')->find();
       return $content['title'];
     }
  public function checkBook(){

         $bookName =input('post.bookName');
       $result=  Db::name('Book')->where(['book_name'=>$bookName])->find();
       if($result){
           return 1;
       }else{
           return 0;
       }
  }

  public function checkPrice(){

         $bookid =input('post.bookid');
         $title =input('post.title');
       //$book=  Db::name('Book')->where(['book_id'=>$bookid])->find();
      $word =$this->totalWords($bookid);
       if($title!="免费"){
           if($word<120000){
               return 1;
           }
       }else{
           return 0;
       }
  }
  //获取章节的总字数
    public function totalWords($bookid){

        $words=  Db::name('Content')->where(['book_id'=>$bookid,'type'=>0,'state'=>1])->sum('number');
        return $words;

    }

    /**
     * @return View
     */
    public function tijiaotwo()
    {
        $bookid =input('post.bookid');
        $result=Db::name('Book')->where(['book_id'=>$bookid])->update(['audit'=>3]);
        if($result){
            return 1;
        }else{
            return 0;
        }
    }
}