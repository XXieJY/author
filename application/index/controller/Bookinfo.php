<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
class Bookinfo extends Controller{
    public function _empty(){
        $this->error('方法不存在');
    }
    public function index($bookid,$current=1){

        if(!is_numeric($bookid)){
            $this->error('参数不合法');
        }

        $book=$this->get_book_info($bookid);//书籍详情

        $other =$this->get_other_book($bookid);//作者其他书籍

        $this->get_book_click($bookid);//更新书籍点击量

        $chapter =$this->get_book_chapter_list($bookid);//章节列表

        $message=$this->get_message($bookid,$current);//评论

        $bookType=$this->echo_book_type($bookid);//同类作品

        $exception =$this->get_new_state($bookid);

        $voteone=$this->get_vipvote($bookid);//票王

        $fans =$this->get_vote($bookid);//第一粉丝

        $fan =$this->get_fans($bookid);//铁杆粉丝
       //print_r($fan);exit();
      return   $this->fetch('',[
          'ok'  =>0,
          'book'  =>$book,
          'other'  =>$other,
          'count'  =>count($other),
          'chapter'  =>$chapter,
          'count1'   =>count($chapter),
          'bookid'   =>$bookid,
          'message'  =>$message,
          'count2'   =>$this->get_message_count($bookid),
          'current'  =>$current,
          'bookid'   =>$bookid,
          'bookType' =>$bookType,
          'vote'     =>$this->echo_vote($bookid),
          'votevip'  =>$this->echo_votevip($bookid),
          'exceptional' =>$exception,
          'voteone'    =>empty($voteone)?'0':$voteone[0],
          'fans'    =>empty($fans)?'0':$fans[0],
          'fan'     =>$fan,
          'a'       =>4

      ]);
    }

    /*
     * 获取书籍的详情
     */
    public function get_book_info($bookid){

        if(cache('book_info'.$bookid))
        {
            $book=cache('book_info'.$bookid);
        }else{
            $where['Book.audit']=1;
            $where['Book.book_id']=$bookid;
            $where['Book.is_show']=1;
            $book =Db::view('Book','book_id,book_name,author_id,author_name,state,vip,cp_id,level,words,book_brief,upload_img,keywords,update_time')
                ->view('BookStatistical','click_total,collection_total,vote_total','BookStatistical.book_id=Book.book_id')
                ->where($where)
                ->find();
            $book['keywords']=$this->resave_keywords($book['keywords']);
            $book['book_brief']=str_replace("\n","</p><p style=\"text-indent: 0.2em;\">",$book['book_brief']);
            $book['jibie']=$this->is_author($book['author_id']);
            $book['image']=$this->touxiang($book['author_id']);
            $book['is_vip']=$this->is_vip($book['author_id']);

            cache('book_info'.$bookid,$book,3600);
        }

         return $book;
    }
    /*
     * 修改标签
     */
    public function resave_keywords($keywords){

           $key =explode("|",$keywords);
           return $key;
    }
    /*
     * 根据author_id查找作者or读者
     */
    public function is_author($author_id){

        $author=Db::name('Writer')->field('user_id')->where(['author_id'=>$author_id])->find();
        if($author['user_id']==0){
            return 0;
        }else{

            $user=Db::name('User')->field('mem_vip')->where(['user_id'=>$author['user_id']])->find();

            return $user['mem_vip'];
        }
    }
    /*
     * 作者头像
     */
    public function touxiang($author_id){

        $author=Db::name('Writer')->field('image')->where(['author_id'=>$author_id])->find();
        return $author['image'];
    }
    /*
     * 判断作者是否是vip用户
     */
    public function is_vip($author_id){
        $author=Db::name('Writer')->field('user_id')->where(['author_id'=>$author_id])->find();
        if($author['user_id']==0){
            return 0;
        }else{
            $user=Db::name('User')->field('days')->where(['user_id'=>$author['user_id']])->find();
            return $user['days'];
        }

    }
    /*
     * 作者其他作品
     */
    public function get_other_book($bookid){

       $author= Db::name('Book')->field('author_id')->where(['book_id'=>$bookid])->find();

       $where['is_show']=1;
       $where['audit']=1;
       $where['book_id']=array('neq',$bookid);

       $book=Db::view('Book','book_id,book_name,upload_img,words')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where($where)
                ->order('update_time desc')
                ->limit(3)
                ->select();

       return $book;
    }
    /*
     * 更新书籍点击量
     */
    public function get_book_click($bookid){
        Db::startTrans();//开启事务
        try{
            $where['time']=date('Y-m-d');
            $where['book_id']=$bookid;
            $result=Db::name('BookTongji')->where($where)->find();
            if(!$result){
                //创建一条新的记录
                $tongji['book_id']=$bookid;
                $tongji['click']=1;
                $tongji['time']=$where['time'];
              $re1=  Db::name('BookTongji')->insert($tongji);
                $data['click_day']=array('exp',"click_day+1");
                $data['click_weeks']=array('exp',"click_weeks+1");
                $data['click_month']=array('exp',"click_month+1");
                $data['click_total']=array('exp',"click_total+1");

              $re2=  Db::name('BookStatistical')->where(['book_id'=>$bookid])->update($data);
              if($re1 && $re2){
                  Db::commit();//提交事务
              }

            }else{
                //更新记录
              $re1=  Db::name('BookTongji')->where($where)->update(['click'=>['exp',"click+1"]]);
                $data['click_day']=array('exp',"click_day+1");
                $data['click_weeks']=array('exp',"click_weeks+1");
                $data['click_month']=array('exp',"click_month+1");
                $data['click_total']=array('exp',"click_total+1");

              $re2=  Db::name('BookStatistical')->where(['book_id'=>$bookid])->update($data);
              if($re1 && $re2){
                  Db::commit();//提交事务
              }
            }
        }catch (\Exception $e){
           Db::rollback();//回滚事务
        }
    }

    /*
     * 查询书籍章节列表
     */
    public function get_book_chapter_list($bookid){
        $where['book_id']=$bookid;
        $where['state']=1;
        $where['status']=0;
        $where['type']=0;
        $chapter=Db::name('Content')->where($where)->field('num,the_price,title')->select();
        return $chapter;
    }
    /*
     * 书籍评论
     */
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
                'message'=>$message,
                'page'   =>$totalPage
            ];
         return $list;
    }
   /*
    * 获取书籍评论条数
    */
   public function get_message_count($bookid){
       $where['book_id']=$bookid;
       $where['f_id']=0;
       $where['status']=1;
       $message= Db::view('BookMessage','z_id,f_id,top,content,thumb,num,time')
           ->view('User','pen_name,portrait,mem_vip,days,is_author','User.user_id=BookMessage.user_id')
           ->where($where)
           ->order(['BookMessage.top'=>'desc','BookMessage.update_time'=>'desc'])
           ->select();
       $length=count($message);

       return $length;

   }
    /*
     * 同类书籍推荐
     */
    public function echo_book_type($bookid){

        $where['is_show']=1;
        $where['audit']=1;
        $where['book_id']=array('neq',$bookid);

          $type=Db::name('Book')->field('type_id')->where(['book_id'=>$bookid])->find();
        $where['Book.type_id']=$type['type_id'];
          $book=Db::view('Book','book_id,book_name,upload_img,words,author_name,book_brief,level')
                     ->view('BookType','book_type','BookType.type_id=Book.type_id')
                     ->where($where)
                     ->limit(12)
                     ->select();
          return $book;
    }
    /*
     * 推荐票
     */
    public function echo_vote($bookid){

        $vote=Db::name('BookStatistical')->field('vote_total')->where(['book_id'=>$bookid])->find();
        return $vote['vote_total'];

    }
    /*
     * 月票
     */
    public function echo_votevip($bookid){

        $votevip=Db::name('BookStatistical')->field('vipvote_total')->where(['book_id'=>$bookid])->find();
        return $votevip['vipvote_total'];

    }
    /*
     * 动态打赏图
     */
    public function get_new_state($bookid){

        $where['UserConsumerecord.type']=array('in','3,4');
        $where['UserConsumerecord.book_id']=$bookid;
        $exceptional=Db::view('UserConsumerecord','dosomething,date')
            ->view('User','pen_name','User.user_id=UserConsumerecord.user_id')
            ->where($where)
            ->order('date desc')
            ->limit(5)
            ->select();

        return $exceptional;

    }

    /*
    * 票王
    */
    public function get_vipvote($bookid){
        $sql="SELECT a.user_id,b.pen_name,b.mem_vip,b.days, SUM(a.count) AS m FROM shudong_user_consumerecord a INNER JOIN shudong_user b ON a.user_id=b.user_id WHERE a.type=? AND a.book_id=? GROUP BY a.user_id ORDER BY m DESC LIMIT 1";
        $vipvote=Db::query($sql,[3,$bookid]);
        return $vipvote;
    }
    /*
     * 第一粉丝
     */
    public function get_vote($bookid){
        $sql="SELECT a.user_id,b.pen_name,b.mem_vip,b.days, SUM(a.count) AS m FROM shudong_user_consumerecord a INNER JOIN shudong_user b ON a.user_id=b.user_id WHERE a.type=? AND a.book_id=? GROUP BY a.user_id ORDER BY m DESC LIMIT 1";
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