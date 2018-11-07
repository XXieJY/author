<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Chapterlist extends Controller{

    public function index($bookid){
        if(!is_numeric($bookid)){
            $this->error('参数不合法');
        }
        $book=$this->get_book($bookid);//获取书籍信息
        $chapter =$this->get_chapter($bookid);//获取小说章节
        $exceptional =$this->get_new_state($bookid);//获得最新滚动图
        $vipvote=$this->get_vipvote($bookid);//票王
        $vote=$this->get_vote($bookid);//第一粉丝
        $fans=$this->get_fans($bookid);//粉丝榜

        $default=$this->get_default_array0();

        $default_fan=$this->get_default_array1();
      //print_r($fans);exit();
        return $this->fetch('',[
            'book'    =>$book,
            'ok'      =>0,
            'chapter'  =>$chapter,
            'exceptional'  =>$exceptional,
            'vipvote'     =>empty($vipvote[0])?$default:$vipvote[0],
            'vote'      =>empty($vote[0])?$default:$vote[0],
            'one'       =>empty($fans[0])?$default_fan[0]:$fans[0],
            'two'       =>empty($fans[1])?$default_fan[1]:$fans[0],
            'fan'    =>empty($fans[2])?$default_fan:$fans,
            'a'   =>3
        ]);
    }

    public function get_book($id){
        $book =Db::name('Book')->field('book_id,book_name,author_name,update_time')->where(['book_id'=>$id])->find();
        return $book;
    }
    //获取默认数组
    public function get_default_array0(){
        $default=[
            'pen_name'  =>'暂无咚者',
            'mem_vip'   =>1
        ];
        return $default;
    }

    //获取默认数组
    public function get_default_array1(){
        $default_fans=[
            0=>[
                'portrait'  =>'portrait.jpg',
                'pen_name'  =>'暂无咚者',
                'fan_value' =>0,
            ],
            1=>[
                'portrait'  =>'portrait.jpg',
                'pen_name'  =>'暂无咚者',
                'fan_value' =>0,
            ],
            2=>[
                'portrait'  =>'portrait.jpg',
                'pen_name'  =>'暂无咚者',
                'fan_value' =>0,
            ]
        ];
        return $default_fans;
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

    /*
     * 动态滚动图
     */
    public function get_new_state($bookid){

        $where['UserConsumerecord.type']=array('in','3,4');
        $where['UserConsumerecord.book_id']=$bookid;
        $exceptional=Db::view('UserConsumerecord','dosomething,date')
                     ->view('User','pen_name','User.user_id=UserConsumerecord.user_id')
                     ->where($where)
                     ->order('date desc')
                     ->limit(4)
                     ->select();

        return $exceptional;

    }
    /*
     * 票王
     */
    public function get_vipvote($bookid){
        $sql="SELECT a.user_id,b.pen_name,b.mem_vip, SUM(a.count) AS m FROM shudong_user_consumerecord a INNER JOIN shudong_user b ON a.user_id=b.user_id WHERE a.type=? AND a.book_id=? GROUP BY a.user_id ORDER BY m DESC LIMIT 1";
        $vipvote=Db::query($sql,[3,$bookid]);
        return $vipvote;
    }
    /*
     * 第一粉丝
     */
    public function get_vote($bookid){
        $sql="SELECT a.user_id,b.pen_name,b.mem_vip, SUM(a.count) AS m FROM shudong_user_consumerecord a INNER JOIN shudong_user b ON a.user_id=b.user_id WHERE a.type=? AND a.book_id=? GROUP BY a.user_id ORDER BY m DESC LIMIT 1";
        $vote=Db::query($sql,[2,$bookid]);
        return $vote;

    }
    /*
     * 铁杆粉丝榜
     */
    public function get_fans($bookid){

        $fans=Db::view('BookFans','user_id,fan_value')
                 ->view('User','portrait,pen_name','User.user_id=BookFans.user_id')
                 ->where(['BookFans.book_id'=>$bookid])
                 ->order('fan_value desc')
                 ->limit(20)
                 ->select();
        return $fans;
    }
}