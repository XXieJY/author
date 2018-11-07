<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
class Book extends Controller{

    public function index(){
        if(request()->isGet()){
            $bookNmae=input('get.book_name');
            $where['book_name']=array('like',"%$bookNmae%");
        }else{
            $where['book_name']="";
        }
        $bookType= $this->book_type();//作品分类
        $book_key =$this->book_key();//作品标签

        $type=input('param.type');
        $key=input('param.key');
        $rank=input('param.rank');
        $time=input('param.time');
        $words=input('param.words');
        $state=input('param.state');
        $where['is_show']=1;
        $where['audit']=1;
       //时间条件
        switch ($time) {
            case 0:
                $this->assign('t0', 'current');
                $this->assign('t1', '');
                $this->assign('t2', '');
                $this->assign('t3', '');
                $this->assign('t4', '');

                break;
            case 1:
                $start=date('Y-m-d H:i:s');
                $end =date("Y-m-d 00:00:00",strtotime("-3 day"));
                $where['Content.time']=array(array('gt',$end),array('lt',$start));
                $this->assign('t0', '');
                $this->assign('t1', 'current');
                $this->assign('t2', '');
                $this->assign('t3', '');
                $this->assign('t4', '');

                break;
            case 2:
                $start=date('Y-m-d H:i:s');
                $end =date("Y-m-d 00:00:00",strtotime("-7 day"));
                $where['Content.time']=array(array('gt',$end),array('lt',$start));
                $this->assign('t0', '');
                $this->assign('t1', '');
                $this->assign('t2', 'current');
                $this->assign('t3', '');
                $this->assign('t4', '');

                break;
            case 3:
                $start=date('Y-m-d H:i:s');
                $end =date("Y-m-d 00:00:00",strtotime("-15 day"));
                $where['Content.time']=array(array('gt',$end),array('lt',$start));
                $this->assign('t0', '');
                $this->assign('t1', '');
                $this->assign('t2', '');
                $this->assign('t3', 'current');
                $this->assign('t4', '');

                break;
            case 4:
                $start=date('Y-m-d H:i:s');
                $end =date("Y-m-d 00:00:00",strtotime("-30 day"));
                $where['Content.time']=array(array('gt',$end),array('lt',$start));
                $this->assign('t0', '');
                $this->assign('t1', '');
                $this->assign('t2', '');
                $this->assign('t3', '');
                $this->assign('t4', 'current');

                break;
        }
        if($type==0){
          $this->assign('type','current');
        }else{
            $this->assign('type','');
            $where['BookType.type_id']=$type;
        }
        if($key==0){
            $this->assign('key1','current');

        }else{
            $this->assign('key1','');
           $keywords =Db::name('SystemKeys')->where(['id'=>$key])->find();
           $keyword=$keywords['key'];
            $where['Book.keywords']=array('like',"%$keyword%");
        }
        //排行榜
        switch ($rank) {
            case 0:$order = 'Content.time desc';
                $this->assign('cl0', 'current');
                $this->assign('cl1', '');
                $this->assign('cl2', '');
                $this->assign('cl3', '');
                $this->assign('cl4', '');
                $this->assign('cl5', '');
                $this->assign('cl6', '');
                $this->assign('cl7', '');
                break;
            case 1:$order = 'BookStatistical.click_weeks desc';
                $this->assign('cl0', '');
                $this->assign('cl1', 'current');
                $this->assign('cl2', '');
                $this->assign('cl3', '');
                $this->assign('cl4', '');
                $this->assign('cl5', '');
                $this->assign('cl6', '');
                $this->assign('cl7', '');
                break;
            case 2:$order = 'BookStatistical.click_month desc';
                $this->assign('cl0', '');
                $this->assign('cl1', '');
                $this->assign('cl2', 'current');
                $this->assign('cl3', '');
                $this->assign('cl4', '');
                $this->assign('cl5', '');
                $this->assign('cl6', '');
                $this->assign('cl7', '');
                break;
            case 3:$order = 'BookStatistical.click_total desc';
                $this->assign('cl0', '');
                $this->assign('cl1', '');
                $this->assign('cl2', '');
                $this->assign('cl3', 'current');
                $this->assign('cl4', '');
                $this->assign('cl5', '');
                $this->assign('cl6', '');
                $this->assign('cl7', '');
                break;
            case 4:$order = 'BookStatistical.vote_weeks desc';
                $this->assign('cl0', '');
                $this->assign('cl1', '');
                $this->assign('cl2', '');
                $this->assign('cl3', '');
                $this->assign('cl4', 'current');
                $this->assign('cl5', '');
                $this->assign('cl6', '');
                $this->assign('cl7', '');
                break;
            case 5:$order = 'BookStatistical.vote_month desc';
                $this->assign('cl0', '');
                $this->assign('cl1', '');
                $this->assign('cl2', '');
                $this->assign('cl3', '');
                $this->assign('cl4', '');
                $this->assign('cl5', 'current');
                $this->assign('cl6', '');
                $this->assign('cl7', '');
                break;
            case 6:$order = 'BookStatistical.vote_total desc';
                $this->assign('cl0', '');
                $this->assign('cl1', '');
                $this->assign('cl2', '');
                $this->assign('cl3', '');
                $this->assign('cl4', '');
                $this->assign('cl5', '');
                $this->assign('cl6', 'current');
                $this->assign('cl7', '');
                break;
            case 7:$order = 'BookStatistical.collection_total desc';
                $this->assign('cl0', '');
                $this->assign('cl1', '');
                $this->assign('cl2', '');
                $this->assign('cl3', '');
                $this->assign('cl4', '');
                $this->assign('cl5', '');
                $this->assign('cl6', '');
                $this->assign('cl7', 'current');
                break;
        }
        //字数
        switch ($words) {
            case 0:
                $this->assign('w0', 'current');
                $this->assign('w1', '');
                $this->assign('w2', '');
                $this->assign('w3', '');
                $this->assign('w4', '');
                $this->assign('w5', '');

                break;
            case 1:
                $start=0;
                $end =300000;
                $where['Book.words']=array(array('gt',$start),array('lt',$end));
                $this->assign('w0', '');
                $this->assign('w1', 'current');
                $this->assign('w2', '');
                $this->assign('w3', '');
                $this->assign('w4', '');
                $this->assign('w5', '');

                break;
            case 2:
                $start=300000;
                $end =500000;
                $where['Book.words']=array(array('gt',$start),array('lt',$end));
                $this->assign('w0', '');
                $this->assign('w1', '');
                $this->assign('w2', 'current');
                $this->assign('w3', '');
                $this->assign('w4', '');
                $this->assign('w5', '');
                break;
            case 3:
                $start=500000;
                $end =1000000;
                $where['Book.words']=array(array('gt',$start),array('lt',$end));
                $this->assign('w0', '');
                $this->assign('w1', '');
                $this->assign('w2', '');
                $this->assign('w3', 'current');
                $this->assign('w4', '');
                $this->assign('w5', '');
                break;
            case 4:
                $start=1000000;
                $end =2000000;
                $where['Book.words']=array(array('gt',$start),array('lt',$end));
                $this->assign('w0', '');
                $this->assign('w1', '');
                $this->assign('w2', '');
                $this->assign('w3', '');
                $this->assign('w4', 'current');
                $this->assign('w5', '');

                break;
            case 5:
                $start=2000000;
                $where['Book.words']=array(array('gt',$start));
                $this->assign('w0', '');
                $this->assign('w1', '');
                $this->assign('w2', '');
                $this->assign('w3', '');
                $this->assign('w4', '');
                $this->assign('w5', 'current');

                break;
        }

        //其他选择
        switch ($state) {
            case 0:
                $this->assign('q0', 'current');
                $this->assign('q1', '');
                $this->assign('q2', '');
                $this->assign('q3', '');
                break;
            case 1:
                $where['vip']=0;
                $this->assign('q0', '');
                $this->assign('q1', 'current');
                $this->assign('q2', '');
                $this->assign('q3', '');
                break;
            case 2:
                $where['Book.state']=2;
                $this->assign('q0', '');
                $this->assign('q1', '');
                $this->assign('q2', 'current');
                $this->assign('q3', '');
                break;
            case 3:
                $where['level']=1;
                $this->assign('q0', '');
                $this->assign('q1', '');
                $this->assign('q2', '');
                $this->assign('q3', 'current');
                break;
        }

       //print_r($where);
        $book=$this->get_rank_list($where,$order);//书籍分类;
        $vote =$this->echo_vote();
        //print_r($vote);
       return $this->fetch('',[
           'ok'           =>5,
           'bookType'    =>$bookType,
           'book_key'    =>$book_key,
           'book'        =>$book,
           'a'           =>1,
           'vote'        =>$vote
       ]);
    }


    /*
     * 作品分类
     */
    public function book_type(){
       $bookType= Db::name('BookType')->select();
       return $bookType;
    }

    /*
     * 作品标签
     */
    public function book_key(){
        $book_key =Db::name('SystemKeys')->select();
        return $book_key;
    }

    public function get_rank_list($where,$order){


        $book=Db::view('Book','book_id,book_name,words,author_name')
              ->view('Content','title,the_price,time','Content.book_id=Book.book_id and Content.num=Book.chapter')
              ->view('BookType','book_type','BookType.type_id=Book.type_id')
              ->view('BookStatistical','click_weeks','BookStatistical.book_id=Book.book_id')
              ->where($where)
              ->order($order)
              ->paginate('20', false, [
                   'query' => Request::instance()->param(),//不丢失已存在的url参数
               ]);
        return $book;
    }
    /*
     * 推荐榜
     *
     */

    public function echo_vote(){

        $vote=Db::view('Book','book_id,book_name,author_name,upload_img,book_brief')
            ->view('BookStatistical','click_total,vote_total,vipvote_total,buy_total,exceptional_total','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('vote_total desc')
            ->limit(2)
            ->select();
        return $vote;
    }
}