<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Read extends Controller{

    public function index($bookid,$num){
         if(!is_numeric($bookid) || !is_numeric($num)){
             $this->error('参数错误');
         }

         $book=$this->get_book_info($bookid);//获取书籍信息
         $chapter=$this->get_chapter($bookid);//获取书籍章节
         // print_r($chapter);exit();
         return $this->fetch('',[
            'book'    =>$book,
             'chapter' =>$chapter
         ]);

    }

    //获取该书的详细信息
    public function get_book_info($bookid){

        $book=Db::name('Book')->where(['book_id'=>$bookid])->find();
        return $book;
    }
    //获取书籍的章节
    public function get_chapter($bookid){
        if(cache('shudong_book_chapter_list'.$bookid)){

            $chapter=cache('shudong_book_chapter_list'.$bookid);

        }else{

            $chapter =Db::name('Content')->field('volume_id,title')->where(['book_id'=>$bookid,'type'=>1])->select();
            $length =count($chapter);
            for($i=0;$i<$length;$i++){
                $chapter[$i]['chapter'] =Db::name('Content')
                    ->field('content_id,title,the_price')
                    ->where(['book_id'=>$bookid,'state'=>1, 'type'=>0,'volume_fid'=>$chapter[$i]['volume_id']])
                    ->select();
            }
           cache('shudong_book_chapter_list'.$bookid,$chapter,3600);
       }

        return $chapter;

    }
}