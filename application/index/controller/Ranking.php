<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Ranking extends Controller{

    public function index(){

       if(cache('click')){
           $click=cache('click');
       }else{
          $click =$this->echo_click();//周点击榜
           cache('click',$click,3600);

       }
        if(cache('collection')){
            $collection=cache('collection');
        }else{
            $collection =$this->echo_collection();//周收藏榜
            cache('collection',$collection,3600);

        }
        if(cache('buy')){
           $buy =cache('buy');
        }else{
            $buy =$this->echo_buy();//畅销榜
            cache('buy',$buy,3600);
        }
        if(cache('vote')){
           $vote=cache('vote');
        }else{
            $vote =$this->echo_vote();//推荐榜
            cache('vote',$vote,3600);
        }
        if(cache('count')){
           $count=cache('count');
        }else{
            $count =$this->echo_count();//更新榜
            cache('count',$count,3600);
        }
       if(cache('new_book_count_month')){
           $newBook=cache('new_book_count_month');
       }else{
           $newBook =$this->echo_new_book();//新书榜
           cache('new_book_count_month',$newBook,3600);
       }
       if(cache('vipvote')){
           $vipvote=cache('vipvote');
       }else{
           $vipvote =$this->echo_vipvote();//月票榜
           cache('vipvote',$vipvote,3600);
       }
       if(cache('tongren_click_weeks')){
           $tongren=cache('tongren_click_weeks');
       }else{
           $tongren=$this->echo_tongren();//同人榜
           cache('tongren_click_weeks',$tongren,3600);
       }
       if(cache('total_words')){
           $words =cache('total_words');
       }else{
           $words =$this->echo_total_words();//总字数榜
           cache('total_words',$words,3600);
       }


        //print_r($words);exit();
      return  $this->fetch('',[
          'ok'            =>4,
          'click'        =>$click,
          'a'            =>$a=4,
          'collection'  =>$collection,
          'b'            =>$b=4,
          'buy'          =>$buy,
          'c'            =>$c=4,
          'vote'         =>$vote,
          'd'            =>$d=4,
          'count'        =>$count,
          'e'           =>$e=4,
          'newBook'      =>$newBook,
          'f'            =>$f=4,
          'vipvote'      =>$vipvote,
          'g'            =>$g=4,
          'tongren'      =>$tongren,
          'h'             =>$h=4,
          'words'         =>$words,
          'j'             =>$j=4,
          'title'        =>''
      ]);
    }
    /*
     * 周点击榜
     */
    public function echo_click(){
           $click=Db::view('Book','book_id,book_name,author_name,upload_img')
                     ->view('BookStatistical','click_weeks','BookStatistical.book_id=Book.book_id')
                     ->view('BookType','book_type','BookType.type_id=Book.type_id')
                     ->where(['is_show'=>1,'audit'=>1])
                     ->order('click_weeks desc')
                     ->limit(10)
                     ->select();
           return $click;
    }
    /*
     * 收藏榜周榜
     */
    public function echo_collection(){
        $collection=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','collection_weeks','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('collection_weeks desc')
            ->limit(10)
            ->select();
        return $collection;
    }
    /*
     * 畅销榜周榜
     */
    public function echo_buy(){
        $buy=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','buy_weeks','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('buy_weeks desc')
            ->limit(10)
            ->select();
        return $buy;
    }

    /*
     * 推荐榜周榜
     */
    public function echo_vote(){
        $vote=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','vote_weeks','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('vote_weeks desc')
            ->limit(10)
            ->select();
        return $vote;

    }
    /*
     * 更新榜周榜
     */
    public function echo_count(){
        $count=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','count_weeks','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('count_weeks desc')
            ->limit(10)
            ->select();
        return $count;
    }
    /*
     * 新书榜按字数
     */
    public function echo_new_book(){
        $newBook=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','count_month','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('create_time desc,BookStatistical.count_month desc')
            ->limit(10)
            ->select();
        return $newBook;

    }
    /*
     * 月票榜月榜
     */
    public function echo_vipvote(){
        $vipvote=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','vipvote_month','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('vipvote_month desc')
            ->limit(10)
            ->select();
        return $vipvote;

    }
    /*
     * 同人榜月榜
     */
    public function echo_tongren(){
        $tongren=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','click_weeks','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1,'Book.type_id'=>16])
            ->order('click_weeks desc')
            ->limit(10)
            ->select();
        return $tongren;
    }
    /*
     * 总字数榜
     */
    public function echo_total_words(){
        $words=Db::view('Book','book_id,book_name,author_name,upload_img,words')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('words desc')
            ->limit(10)
            ->select();
        return $words;
    }
}