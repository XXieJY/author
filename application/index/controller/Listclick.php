<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
class Listclick extends Controller{

    private $title;

    public function index($p,$current=1){

         $xin='';
         $type=0;
         if($p=="click"){
             //点击榜
             $bangs="click_weeks";
             $bang="click";
             $this->title="点击榜";
         }elseif($p=="collect"){
             //收藏榜
             $bangs="collection_weeks";
             $bang="collection";
             $this->title="收藏榜";
         }elseif($p=="buy"){
             //畅销榜
             $bangs="buy_weeks";
             $bang="buy";
             $this->title="畅销榜";
         }elseif($p=="vote"){
             //推荐榜
             $bangs="vote_weeks";
             $bang="vote";
             $this->title="推荐榜";
         }elseif($p=="count"){
             //更新榜
             $bangs="count_weeks";
             $bang="count";
             $this->title="更新榜";
         }elseif ($p=="vipvote"){
             //月票榜
             $bangs="vipvote_weeks";
             $bang="vipvote";
             $xin="ab";
             $this->title="月票榜";
         }elseif ($p=="newBook"){
             //新书榜
             $bangs="count_month";
             $bang="count";
             $xin="abc";
             $this->title="新书榜";
         }elseif($p=="tongren"){
             //同人榜
              $bangs="click_weeks";
             $bang="click";
              $type=1;
             $this->title="同人榜";
         }elseif ($p=="words"){
             //总字数榜
             $bangs="count_total";
             $bang="count";
             $xin="a";
             $this->title="总字数榜";
         }
        if(request()->isPost()){
            $jump =input('post.jump');
            if(!is_numeric($jump)){
                $this->error('必须输入数字');
            }
            $current=$jump;
        }
        $list =$this->echo_click_weeks($current,$bangs,$type);//周点击榜

        $jinzhu =$this->jinzhu();//金主榜周榜

        return $this->fetch('',[
            'ok'   =>4,
            'weeks'   =>$list['weeks'],
            'a'     =>1,
            'current'  =>$current,
            'page'    =>$list['page'],
            'bang'     =>$bang,
            'bangs'     =>$bangs,
            'xin'      =>$xin,
            'type'     =>$type,
            'p'         =>$p,
            'jinzhu'    =>$jinzhu,
            'b'         =>1,
            'title'    =>$this->title

        ]);
    }

    /*
     * 点击榜周榜
     */
    public function echo_click_weeks($current,$bang,$type=""){
        $list=[];
        $where['Book.is_show']=1;
        $where['Book.audit']=1;
        if($type==1){
            //同人榜
            $where['Book.type_id']=16;
        }
        //分页变量
        $pageSize = 10;//每页显示的记录数
        $totalRow = 0;//总记录数
        $totalPage = 0;//总页数
        $start = ($current-1)*$pageSize;//每页记录的起始值
        $totalRow=Db::view('Book','book_id,book_name,author_name,upload_img,book_brief')
                        ->view('Content','title,num,time','Content.book_id=Book.book_id and Content.num=Book.chapter')
                        ->view('BookStatistical',"{$bang}",'BookStatistical.book_id=Book.book_id')
                        ->where($where)
                       ->count();
        $totalPage =ceil($totalRow/$pageSize);

        $click_weeks=Db::view('Book','book_id,book_name,author_name,upload_img,book_brief')
                      ->view('Content','title,num,time','Content.book_id=Book.book_id and Content.num=Book.chapter')
                      ->view('BookStatistical',"{$bang}",'BookStatistical.book_id=Book.book_id')
                      ->where($where)
                      ->limit($start,$pageSize)
                      ->order("BookStatistical.{$bang} desc")
                      ->select();
        $list=[
            'page'    =>$totalPage,
            'weeks'   =>$click_weeks
        ];
        return $list;
    }
    /*
     * 金主榜
     */
    public function jinzhu(){
        $today=date('Y-m-d H:i:s');
        $endToday=date("Y-m-d 00:00:00",strtotime("-7 day"));
        $sql="SELECT a.user_id, b.pen_name,portrait, SUM(a.money) AS money from shudong_user_consumerecord a LEFT  JOIN shudong_user b ON a.user_id=b.user_id WHERE a.date BETWEEN ? AND ?  GROUP BY a.user_id  ORDER BY money DESC LIMIT 0,20";
        $info=Db::query($sql,[$endToday, $today]);
        return $info;
    }
}