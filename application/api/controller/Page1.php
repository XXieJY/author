<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
class Page1 extends Controller{

    public function pre_page1(){

        $bookid=input('post.bookid');
        $current=input('post.current');
        $current=$current-1;
        $message= $this->get_message($bookid,$current);
        if(!$message){
            return 1;
        }else{
            return $this->fetch('',[
                'message' =>$message,
                'current'=>$current,
                'bookid' =>$bookid
            ]);
        }

    }
    public function next_page1(){

        $bookid=input('post.bookid');
        $current=input('post.current');
        $current=$current+1;
       $message= $this->get_message($bookid,$current);
       if(!$message){
           return 1;
       }else{
           return $this->fetch('',[
               'message' =>$message,
               'current'=>$current,
               'bookid' =>$bookid
           ]);
       }

    }

    public function one_page(){

    $bookid=input('post.bookid');
    $current=input('post.current');
    $current=$current-1;
    $message= $this->get_message($bookid,$current);
    if(!$message){
        return 1;
    }else{
        return $this->fetch('',[
            'message' =>$message,
            'current'=>$current,
            'bookid' =>$bookid
        ]);
    }

}

    public function two_page(){

        $bookid=input('post.bookid');
        $current=input('post.current');
        $current=$current+1;
        $message= $this->get_message($bookid,$current);
        if(!$message){
            return 1;
        }else{
            return $this->fetch('',[
                'message' =>$message,
                'current'=>$current,
                'bookid' =>$bookid
            ]);
        }

    }

    public function get_message($bookid,$current){
        $list=[];
        $where['book_id']=$bookid;
        $where['f_id']=0;
        $where['status']=1;
        //分页变量
        $pageSize = 5;//每页显示的记录数
        $totalRow = 0;//总记录数
        $totalPage = 0;//总页数
        $start = ($current-1)*$pageSize;//每页记录的起始值
        $totalRow=Db::view('BookMessage','z_id,f_id,top,content,thumb,num,time')
            ->view('User','pen_name,portrait,mem_vip,days,is_author','User.user_id=BookMessage.user_id')
            ->where($where)
            ->count();
        $totalPage =ceil($totalRow/$pageSize);
        $message= Db::view('BookMessage','z_id,f_id,top,content,thumb,num,time')
            ->view('User','pen_name,portrait,mem_vip,days,is_author','User.user_id=BookMessage.user_id')
            ->where($where)
            ->limit($start,$pageSize)
            ->order(['BookMessage.top'=>'desc','BookMessage.update_time'=>'desc'])
            ->select();
        $length=count($message);
        for ($i=0;$i<$length;$i++){
            $map['status']=1;
            $map['f_id']=$message[$i]['z_id'];
            $message[$i]['msg']=Db::view('BookMessage','z_id,f_id,top,content,thumb,num,time')
                ->view('User','pen_name,portrait,mem_vip,days,is_author','User.user_id=BookMessage.user_id')
                ->where($map)
                ->order('BookMessage.top desc,BookMessage.update_time desc')
                ->select();
        }
        $list=[
            'message'  =>$message,
            'page'     =>$totalPage
            ];
        return $list;
    }
}