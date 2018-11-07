<?php
namespace app\author\controller;
use think\Controller;
use think\Db;
class Contab extends Controller{
//
//    //系统发送月票
//    public function send_votevip(){
//
//        $user=Db::name('User')->where(['days'=>['gt',0]])->field('user_id,pen_name,vipvote,days')->select();
//
//        $data['vipvote']=array('exp',"vipvote+1");
//        $count=count($user);
//        for ($i=0;$i<$count;$i++){
//            if($user[$i]['days']>=31){
//
//             $result=  Db::name('User')->where(['user_id'=>$user[$i]['user_id']])->update($data);
//
//             if($result){
//
//                 //记录用户的月票的
//                 $yue['user_id'] =$user[$i]['user_id'];
//                 $yue['type'] =2;
//                 $yue['count'] =1;
//                 $yue['dosomething'] ="充值vip期间书咚按月发放一张月票";
//                 $yue['time'] =date('Y-m-d H:i:s');
//                 Db::name('UserDobing')->insert($yue);
//             }
//
//            }else{
//
//               //给用户发消息
//                $msg=[
//                    'user_id'  =>$user[$i]['user_id'],
//                    'state'    =>0,//表示还未查看
//                    'type'     =>0,//表示系统发的消息
//                    'title'   =>"书咚官方消息",
//                    'content' =>"尊敬的Vip用户，您的Vip月票已发放完毕~~",
//                    'time'    =>date('Y-m-d H:i:s')
//                ];
//                Db::name('UserMessage')->insert($msg);
//
//            }
//
//
//        }
//
//    }
//
//    //系统发放订阅月票
//    public function send_dingyue(){
//
//          $time=$this->GetMonth();
//          $sql="SELECT user_id, SUM(money) AS m FROM shudong_user_consumerecord WHERE type=1 AND date like '%{$time}%'  GROUP BY user_id";
//          $user= Db::query($sql);
//          $count=count($user);
//         for($i=0;$i<$count;$i++){
//
//             if($user[$i]['m']>=1500 && $user[$i]['m']<3000){
//                $re1= Db::name('User')->where(['user_id'=>$user[$i]['user_id']])->update(['vipvote'=>['exp',"vipvote+1"]]);
//                 if($re1){
//
//                     //记录用户的月票的
//                     $yue['user_id'] =$user[$i]['user_id'];
//                     $yue['type'] =2;
//                     $yue['count'] =1;
//                     $yue['dosomething'] ="订阅消费在1500咚币与3000咚币之间，书咚每月赠送1张月票";
//                     $yue['time'] =date('Y-m-d H:i:s');
//                     Db::name('UserDobing')->insert($yue);
//                 }
//
//             }elseif ($user[$i]['m']>=3000 && $user[$i]['m']<4500){
//                $re2= Db::name('User')->where(['user_id'=>$user[$i]['user_id']])->update(['vipvote'=>['exp',"vipvote+2"]]);
//                 if($re2){
//                     //记录用户的月票的
//                     $yue['user_id'] =$user[$i]['user_id'];
//                     $yue['type'] =2;
//                     $yue['count'] =2;
//                     $yue['dosomething'] ="订阅消费在3000咚币与4500咚币之间，书咚每月赠送2张月票";
//                     $yue['time'] =date('Y-m-d H:i:s');
//                     Db::name('UserDobing')->insert($yue);
//                 }
//             }elseif ($user[$i]['m']>=4500 && $user[$i]['m']<6000){
//                $re3= Db::name('User')->where(['user_id'=>$user[$i]['user_id']])->update(['vipvote'=>['exp',"vipvote+3"]]);
//                if($re3){
//                    //记录用户的月票的
//                    $yue['user_id'] =$user[$i]['user_id'];
//                    $yue['type'] =2;
//                    $yue['count'] =3;
//                    $yue['dosomething'] ="订阅消费在4500咚币与6000咚币之间，书咚每月赠送3张月票";
//                    $yue['time'] =date('Y-m-d H:i:s');
//                    Db::name('UserDobing')->insert($yue);
//                }
//
//             }elseif ($user[$i]['m']>=6000 && $user[$i]['m']<7500){
//                $re4= Db::name('User')->where(['user_id'=>$user[$i]['user_id']])->update(['vipvote'=>['exp',"vipvote+4"]]);
//                 if($re4){
//
//                     //记录用户的月票的
//                     $yue['user_id'] =$user[$i]['user_id'];
//                     $yue['type'] =2;
//                     $yue['count'] =4;
//                     $yue['dosomething'] ="订阅消费在6000咚币与7500咚币之间，书咚每月赠送4张月票";
//                     $yue['time'] =date('Y-m-d H:i:s');
//                     Db::name('UserDobing')->insert($yue);
//                 }
//
//             }elseif ($user[$i]['m']>=7500 ){
//                $re5= Db::name('User')->where(['user_id'=>$user[$i]['user_id']])->update(['vipvote'=>['exp',"vipvote+5"]]);
//                 if($re5){
//
//                     //记录用户的月票的
//                     $yue['user_id'] =$user[$i]['user_id'];
//                     $yue['type'] =2;
//                     $yue['count'] =5;
//                     $yue['dosomething'] ="订阅消费在7500以上，书咚每月赠送5张月票";
//                     $yue['time'] =date('Y-m-d H:i:s');
//                     Db::name('UserDobing')->insert($yue);
//                 }
//
//             }
//         }
//
//    }
//
//   private function GetMonth($sign="1")
//    {
//        //得到系统的年月
//        $tmp_date=date("Ym");
//        //切割出年份
//        $tmp_year=substr($tmp_date,0,4);
//        //切割出月份
//        $tmp_mon =substr($tmp_date,4,2);
//        $tmp_nextmonth=mktime(0,0,0,$tmp_mon+1,1,$tmp_year);
//        $tmp_forwardmonth=mktime(0,0,0,$tmp_mon-1,1,$tmp_year);
//        if($sign==0){
//            //得到当前月的下一个月
//            return $fm_next_month=date("Y-m",$tmp_nextmonth);
//        }else{
//            //得到当前月的上一个月
//            return $fm_forward_month=date("Y-m",$tmp_forwardmonth);
//        }
//    }
//
    //统计全勤
    public function tongji(){
          $time =date('Y-m-d',strtotime('-1 day'));

          $content =Db::name('Content');
          $book =Db::name('Book');
         $bookArr= $book->where(['audit'=>1,'is_show'=>1])->field('book_id')->select();
          if(is_array($bookArr)){
              //查找今天更新的章节
              $count =count($bookArr);
              for ($i=0;$i<$count;$i++){
                  $where['time'] =array('like',"%$time%");
                  $where['book_id'] =$bookArr[$i]['book_id'];
                  $where['state'] =1;
                  $where['status']=0;
                  $where['type'] =0;
                 $chapter= $content->where($where)->field('book_id,number,time')->find();
                 $books=$book->where(['book_id'=>$bookArr[$i]['book_id']])->find();
                 $words=$content->where($where)->sum('number');
                 if(!is_array($chapter)){

                     $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full'=>['exp',"full+1"]]);

                 }else{
                     if($words>=2000 && $words<4000 && $books['contract_id']<7){

                         if($books['full_id']>2 && $books['full']==0 ){

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full'=>['exp',"full+1"]]);

                         }elseif ($books['full_id']>2 && $books['full']==1){

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full_id'=>2]);

                         }elseif ($books['full_id']==0){

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full_id'=>2]);

                         }

                     }elseif ($words>=4000 && $words<8000 && $books['contract_id']<7){

                         if($books['full_id']>3 && $books['full']==0){

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full'=>['exp',"full+1"]]);

                         }elseif ($books['full_id']>3 && $books['full']==1){

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full_id'=>3]);

                         }elseif  ($books['full_id']==0){

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full_id'=>3]);
                         }

                     }elseif ($words>=8000 && $books['contract_id']<7){

                         if($books['full_id']==0) {

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full_id'=>4]);
                         }
                     }elseif ($words<2000 && $books['contract_id']<7){

                         $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full'=>['exp',"full+1"]]);

                     }elseif ($words>=4000 && $words<6000 && $books['contract_id']>=7){

                         if($books['full_id']==5 && $books['full']==0 ){

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full'=>['exp',"full+1"]]);

                         }elseif ($books['full_id']==5 && $books['full']==1){

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full_id'=>3]);

                         }elseif ($books['full_id']==0){
                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full_id'=>3]);
                         }

                     }elseif ($words>=6000 && $books['contract_id']>=7){

                         if($books['full_id']!=3) {

                             $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full_id'=>5]);
                         }
                     }elseif ($words<4000 && $books['contract_id']>=7){

                         $book->where(['book_id'=>$bookArr[$i]['book_id']])->update(['full'=>['exp',"full+1"]]);
                     }
                 }

              }

          }

    }

    /*
     * @金主榜周榜
     */
//  public function get_jinzhu_week(){
//
//      $nowTime =date('Y-m-d H:i:s');
//      $endToday=date("Y-m-d 00:00:00",strtotime("-7 day"));
//
//      $sql="SELECT a.user_id, b.pen_name,portrait, SUM(a.money) AS money from shudong_user_consumerecord a LEFT  JOIN shudong_user b ON a.user_id=b.user_id WHERE a.date BETWEEN ? AND ?  GROUP BY a.user_id  ORDER BY money DESC LIMIT 0,10";
//      $info=Db::query($sql,[$endToday, $nowTime]);
//     // print_r($info);exit();
//      $count =count($info);
//
//      for ($i=0;$i<$count;$i++){
//           $a =$i+1;
//          if($i==0){
//            $re1=  Db::name('User')->where(['user_id'=>$info[$i]['user_id']])->update(['vote'=>['exp',"vote+9"]]);
//             if($re1){
//                 //给用户发消息
//                 $msg=[
//                     'user_id'  =>$info[$i]['user_id'],
//                     'state'    =>0,//表示还未查看
//                     'type'     =>0,//表示系统发的消息
//                     'title'   =>"书咚官方消息",
//                     'content' =>"恭喜你获得上周金主榜周榜第".$a."名。获得2天推书权+9张推荐票，若行使推书权，请加书咚大刀QQ：3040277546，推书权详见金主榜右上角说明~",
//                     'time'    =>date('Y-m-d H:i:s')
//                 ];
//                 Db::name('UserMessage')->insert($msg);
//
//             }
//
//          }elseif ($i>=1 && $i<5){
//
//             $re2= Db::name('User')->where(['user_id'=>$info[$i]['user_id']])->update(['vote'=>['exp',"vote+7"]]);
//
//              if($re2){
//                  //给用户发消息
//                  $msg=[
//                      'user_id'  =>$info[$i]['user_id'],
//                      'state'    =>0,//表示还未查看
//                      'type'     =>0,//表示系统发的消息
//                      'title'   =>"书咚官方消息",
//                      'content' =>"恭喜你获得上周金主榜周榜第".$a."名,获得7张推荐票，请尽快使用哦~",
//                      'time'    =>date('Y-m-d H:i:s')
//                  ];
//                  Db::name('UserMessage')->insert($msg);
//
//              }
//
//
//          }else{
//
//             $re3= Db::name('User')->where(['user_id'=>$info[$i]['user_id']])->update(['vote'=>['exp',"vote+5"]]);
//             if($re3){
//                 //给用户发消息
//                 $msg=[
//                     'user_id'  =>$info[$i]['user_id'],
//                     'state'    =>0,//表示还未查看
//                     'type'     =>0,//表示系统发的消息
//                     'title'   =>"书咚官方消息",
//                     'content' =>"恭喜你获得上周金主榜周榜第".$a."名,获得5张推荐票，请尽快使用哦~",
//                     'time'    =>date('Y-m-d H:i:s')
//                 ];
//                 Db::name('UserMessage')->insert($msg);
//
//
//             }
//          }
//
//      }
//
//  }

//  /*
//   * @金主榜月榜
//   */
//    public function get_jinzhu_month(){
//
//        $time=$this->GetMonth();
//
//        $sql="SELECT a.user_id, b.pen_name,portrait, SUM(a.money) AS money from shudong_user_consumerecord a LEFT  JOIN shudong_user b ON a.user_id=b.user_id WHERE a.date like '%{$time}%'  GROUP BY a.user_id  ORDER BY money DESC LIMIT 0,10";
//        $info=Db::query($sql);
//
//        $count =count($info);
//
//        for ($i=0;$i<$count;$i++){
//            if($i==0){
//               $re1= Db::name('User')->where(['user_id'=>$info[$i]['user_id']])->update(['vipvote'=>['exp',"vipvote+9"]]);
//               if($re1){
//                   //记录用户的月票的
//                   $yue['user_id'] =$info[$i]['user_id'];
//                   $yue['type'] =2;
//                   $yue['count'] =9;
//                   $yue['dosomething'] ="金主榜月榜排名第一名，书咚每月赠送9张月票";
//                   $yue['time'] =date('Y-m-d H:i:s');
//                   Db::name('UserDobing')->insert($yue);
//                   //给用户发消息
//                   $msg=[
//                       'user_id'  =>$info[$i]['user_id'],
//                       'state'    =>0,//表示还未查看
//                       'type'     =>0,//表示系统发的消息
//                       'title'   =>"书咚官方消息",
//                       'content' =>"恭喜你获得上月金主榜月榜第1名,获得5天推书权+9张月票，若行使推书权，请加书咚大刀QQ：3040277546，推书权详见金主榜右上角说明~",
//                       'time'    =>date('Y-m-d H:i:s')
//                   ];
//                   Db::name('UserMessage')->insert($msg);
//
//               }
//
//            }elseif ($i>=1 && $i<5){
//
//               $re2= Db::name('User')->where(['user_id'=>$info[$i]['user_id']])->update(['vipvote'=>['exp',"vipvote+7"]]);
//               if($re2){
//                   //记录用户的月票的
//                   $a=$i+1;
//                   $yue['user_id'] =$info[$i]['user_id'];
//                   $yue['type'] =2;
//                   $yue['count'] =7;
//                   $yue['dosomething'] ="金主榜月榜排名第".$a."名，书咚每月赠送7张月票";
//                   $yue['time'] =date('Y-m-d H:i:s');
//                   Db::name('UserDobing')->insert($yue);
//                   //给用户发消息
//                   $msg=[
//                       'user_id'  =>$info[$i]['user_id'],
//                       'state'    =>0,//表示还未查看
//                       'type'     =>0,//表示系统发的消息
//                       'title'   =>"书咚官方消息",
//                       'content' =>"恭喜你获得上月金主榜月榜第".$a."名，获得7张月票，请尽快使用哦~",
//                       'time'    =>date('Y-m-d H:i:s')
//                   ];
//                   Db::name('UserMessage')->insert($msg);
//
//               }
//
//            }else{
//
//               $re3= Db::name('User')->where(['user_id'=>$info[$i]['user_id']])->update(['vipvote'=>['exp',"vipvote+5"]]);
//                if($re3){
//                    //记录用户的月票的
//                    $b=$i+1;
//                    $yue['user_id'] =$info[$i]['user_id'];
//                    $yue['type'] =2;
//                    $yue['count'] =5;
//                    $yue['dosomething'] ="金主榜月榜排名第".$b."名，书咚每月赠送5张月票";
//                    $yue['time'] =date('Y-m-d H:i:s');
//                    Db::name('UserDobing')->insert($yue);
//
//                    //给用户发消息
//                    $msg=[
//                        'user_id'  =>$info[$i]['user_id'],
//                        'state'    =>0,//表示还未查看
//                        'type'     =>0,//表示系统发的消息
//                        'title'   =>"书咚官方消息",
//                        'content' =>"恭喜你获得上月金主榜月榜第".$b."名，获得5张月票，请尽快使用哦~",
//                        'time'    =>date('Y-m-d H:i:s')
//                    ];
//                    Db::name('UserMessage')->insert($msg);
//
//                }
//            }
//
//        }
//
//    }
//
//    /*
//     * 推荐榜单周榜
//     */
//
//    public function get_vote_week(){
//
//       $vote=   Db::view('Book','book_name,author_name')
//              ->view('BookStatistical','vote_weeks','BookStatistical.book_id=Book.book_id')
//              ->where(['Book.is_show'=>1,'Book.audit'=>1])
//              ->order('BookStatistical.vote_weeks desc')
//              ->limit(20)
//              ->select();
//
//       // $sql="SELECT t1.book_id,SUM(vote) AS vo,t2.book_name,t2.author_name FROM shudong_book_tongji AS t1 INNER JOIN shudong_book AS t2 ON t1.book_id=t2.book_id WHERE time BETWEEN '2018-07-16' AND '2018-07-22' GROUP BY book_id ORDER BY vo DESC LIMIT 0,20";
////
////
//      // $vote =Db::query($sql);
//
//      // print_r($vote);exit();
//
//        for ($i=0;$i<count($vote);$i++){
//
//           //数据入库
//           $data['book_name']=$vote[$i]['book_name'];
//           $data['author_name']=$vote[$i]['author_name'];
//           $data['vote'] =$vote[$i]['vote_weeks'];
//           $data['type']=1;
//           $data['time'] =date('Y-m-d');
//           Db::name('BookVoteTongji')->insert($data);
//       }
//
//    }
//
//    /*
//     * 推荐榜单月榜
//     */
//    public function get_vote_month(){
//
//
//        $time =date('Y-m',strtotime('-1 month'));
//
//
//        $sql="SELECT book_id,SUM(vote) AS vo FROM shudong_book_tongji WHERE time LIKE '%{$time}%' GROUP BY book_id ORDER BY vo DESC LIMIT 0,25";
//
//        $vote= Db::query($sql);
//       $count =count($vote);
//        for ($i=0;$i<$count;$i++){
//            $book =$this->get_book_info($vote[$i]['book_id']);
//            $data['book_name']  =$book['book_name'];
//            $data['author_name'] =$book['author_name'];
//            $data['type']  =2;
//            $data['vote']=$vote[$i]['vo'];
//            $data['time']=date('Y-m-30',strtotime('-1 month'));
//            Db::name('BookVoteTongji')->insert($data);
//        }
//    }
//
//    /*
//     * 获取书籍信息
//     */
//    private function get_book_info($bookid){
//
//        if(!is_numeric($bookid)){
//            $this->error('参数错误');
//        }
//       $book= Db::name('Book')->where(['book_id'=>$bookid])->field('book_name,author_name')->find();
//       return $book;
//    }
//
//    /*
//     * 定时调节书籍章节问题
//     */
    public function adjust(){

        $book =Db::name('Book')->where(['is_show'=>1,'audit'=>1])->field('book_id,book_name,chapter')->select();
        $count =count($book);
        for ($i=0;$i<$count;$i++){

            if($book[$i]['chapter']!=$this->total_num($book[$i]['book_id'])){
                Db::name('Book')->where(['book_id'=>$book[$i]['book_id']])->update(['chapter'=>$this->total_num($book[$i]['book_id'])]);
            }
        }
    }

    /*
     * 获取书籍章节的总章节数
     */
    private function total_num($bookid){
        $where['book_id']=$bookid;
        $where['status'] =0;
        $where['state'] =1;
        $where['type']=0;
      $chapter=  Db::name('Content')->where($where)->field('num')->order('num desc')->find();
      return $chapter['num'];
    }
//
//    /*
//     * 定时调节书籍的总字数问题
//     */
//
    public function words(){

        $book =Db::name('Book')->where(['is_show'=>1,'audit'=>1])->field('book_id,book_name,words')->select();
        $count =count($book);
        for ($i=0;$i<$count;$i++){

            if($book[$i]['words']!=$this->total_words($book[$i]['book_id'])){

                //更新字数
                Db::name('Book')->where(['book_id'=>$book[$i]['book_id']])->update(['words'=>$this->total_words($book[$i]['book_id'])]);
                Db::name('BookStatistical')->where(['book_id'=>$book[$i]['book_id']])->update(['count_total'=>$this->total_words($book[$i]['book_id'])]);

            }

        }

    }

    /*
     * 获取书籍总字数
     */
    private function total_words($bookid){
        $where['book_id']=$bookid;
        $where['status'] =0;
        $where['state'] =1;
        $where['type']=0;
       $words= Db::name('Content')->where($where)->sum('number');
       return $words;
    }
//    /*
//     * 定时器按天减去vip会员的天数
//     */
////    public function user_vip(){
////
////      $sql="UPDATE shudong_user SET `days` = `days`-1 WHERE `days`>0";
////      Db::query($sql);
////
////    }
//
//
//    /*
//     * 定时器按天清楚数据
//     */
////    public function statistical_clean_day(){
////
////     $sql="	UPDATE `shudong_book_statistical` SET click_day=0,collection_day=0,buy_day=0,count_day=0,exceptional_day=0,vote_day=0,vipvote_day=0,money_day=0";
////     Db::query($sql);
////    }
//
//
//    /*
//     * 定时器按周清空周数据
//     *
//     */
////    public function statistical_clean_week()
////    {
////        $sql="UPDATE `shudong_book_statistical` SET click_weeks=0,collection_weeks=0,buy_weeks=0,count_weeks=0,exceptional_weeks=0,vote_weeks=0,vipvote_weeks=0,money_weeks=0";
////
////        Db::query($sql);
////    }
//    /*
//     * 定时器按月清空数据
//     */
////    public function statistical_clean_month(){
////
////        $sql="UPDATE `shudong_book_statistical` SET click_month=0,collection_month=0,buy_month=0,count_month=0,exceptional_month=0,vote_month=0,vipvote_month=0,money_month=0";
////        Db::query($sql);
////    }
//    /*
//     * 定时器每天清楚推荐票
//     */
////    public function user_vote(){
////
////        $sql ="UPDATE shudong_user SET `vote`=0";
////        Db::query($sql);
////    }
//   /*
//    * 定时器每月清楚月票
//    */
////   public function user_vipvote(){
////
////       $sql ="UPDATE shudong_user SET `vipvote`=0";
////       Db::query($sql);
////   }
//
///*
// *
// *
// */
// public function book_full(){
//
//      $sql="UPDATE shudong_book SET `full`=0,`full_id`=0";
//      Db::query($sql);
//
// }
//
//
//
//    /*
//     * 定时器每分钟定时更新章节
//     */
//
    public function content_dingshi(){
       Db::startTrans();//开启事务
        try{
            $nowTime =strtotime(date('Y-m-d H:i:s'));
            //获取章节的定时更新章节

                $chapter=  Db::name('Content')->where(['status'=>1,'state'=>1])->select();


            $count =count($chapter);
            for($i=0;$i<$count;$i++){
                if(strtotime($chapter[$i]['update_time'])<=$nowTime){
                    //定时发布章节并更新字数
                    $conn['status'] =0;
                    $conn['time'] =$chapter[$i]['update_time'];
                  $re1=  Db::name('Content')->where(['content_id'=>$chapter[$i]['content_id']])->update($conn);
                  //更新书籍字数的章节数
                    $word=$chapter[$i]['number'];
                    $data['words']=array('exp',"words+$word");
                    $data['chapter'] =array('exp',"chapter+1");
                  $re2=  Db::name('Book')->where(['book_id'=>$chapter[$i]['book_id']])->update($data);

                 if($chapter[$i]['the_price']>0){
                    $book= Db::name('Book')->where(['book_id'=>$chapter[$i]['book_id']])->find();
                    if($book['vip']==0){
                      $re4=  Db::name('Book')->where(['book_id'=>$chapter[$i]['book_id']])->update(['vip'=>1,'vip_time'=>date('Y-m-d H:i:s')]);
                    }
                 }
                  //更新统计表字数
                    $st['count_day']= array('exp',"count_day+$word");
                    $st['count_weeks'] =array('exp',"count_weeks+$word");
                    $st['count_month'] =array('exp',"count_month+$word");
                    $st['count_total'] =array('exp',"count_total+$word");

                   $re3= Db::name('BookStatistical')->where(['book_id'=>$chapter[$i]['book_id']])->update($st);

                       if($re1 && $re2 && $re3){

                           Db::commit();//提交事务
                       }
                }

            }

        }catch (\Exception $e){
            Db::rollback();//回滚事务
        }

    }
//
//    //定时器保存全勤数据
//    public function quanqin(){
//        $time =date('Y-m-02 23:59:59',strtotime('-1 month'));
//
//        $list=$arr=[];
//
//        $book =Db::view('Book','book_id,book_name,author_name,full,full_id,contract_id')
//            ->view('BookEdit','edit_name','BookEdit.e_id=Book.e_id')
//            ->view('Content','title,time','Content.book_id=Book.book_id')
//            ->where(['Book.contract_id'=>['gt',2],'Book.full'=>['lt',2],'is_show'=>1,'audit'=>1,'Book.full_id'=>['gt',1],'Content.type'=>0,'Content.num'=>1])
//            ->select();
//        for ($i=0;$i<count($book);$i++){
//
//            if(strtotime($book[$i]['time'])>strtotime($time)){
//
//            }else{
//                $list[]=$book[$i];
//            }
//        }
//        for ($i=0;$i<count($list);$i++){
//            $titleTime=date('Y-m-d',strtotime($list[$i]['time']));
//            if($titleTime==date('Y-m-02') && $list[$i]['full']==1){
//
//            }else{
//                $arr[]=$list[$i];
//            }
//        }
//        $length =count($arr);
//        for ($i=0;$i<$length;$i++){
//            $data=[
//               'book_id'  =>$arr[$i]['book_id'],
//                'book_name' =>$arr[$i]['book_name'],
//                'author_name' =>$arr[$i]['author_name'],
//                'contract' =>$arr[$i]['contract_id'],
//                'e_name'  =>$arr[$i]['edit_name'],
//                'full_id'  =>$arr[$i]['full_id'],
//                'full'  =>$arr[$i]['full'],
//                'time'  =>date('Y-m-d',strtotime('-1 month'))
//            ];
//            Db::name('BookQuanqin')->insert($data);
//        }
//
//    }
//
//    //每月一号定时获取人气王排名
//    public function renqiwang(){
//
//        $time =$this->GetMonth();
//
//       // $time=date('Y-m');
//
//        $sql="SELECT be_code,COUNT(user_id) AS c FROM shudong_user  WHERE bind_time LIKE '%{$time}%' AND be_code!='' AND is_user=1 GROUP BY be_code ORDER BY c DESC LIMIT 15";
//        $user= Db::query($sql);
//
//        //print_r($user);exit();
//        foreach ($user as $k=>$v){
//            $info =$this->getUserInfo($v['be_code']);
//            $user[$k]['pen_name']=$info['pen_name'];
//            $user[$k]['phone']  =$info['phone'];
//            $user[$k]['create_time'] =$info['create_time'];
//
//            $data =[
//               'user_id'  =>$info['user_id'],
//                'pen_name' =>$info['pen_name'],
//                'phone'    =>$info['phone'],
//                'code'     =>$v['be_code'],
//                'num'      =>$v['c'],
//                'time'     =>$time."-30"
//            ];
//
//            Db::name('BookVote')->insert($data);
//
//
//        }
//
//
//
//    }
//
//    //获取用户信息
//    public function getUserInfo($code){
//
//        $user= Db::name('User')->where(['code'=>$code])->field('user_id,pen_name,phone,create_time')->find();
//
//        return $user;
//    }
//
//
//
}