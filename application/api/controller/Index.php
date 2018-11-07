<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
class index extends Controller{
    public function click_zhou(){
        if(cache('click_weeks')){
            $click=cache('click_weeks');
        }else{
            $click=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','click_weeks','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('click_weeks desc')
                ->limit(10)
                ->select();
            cache('click_week',$click,3600);
        }

       if(!$click){
           return 1;
       }else{
           return $this->fetch('',[
               'click'  =>$click,
               'a'      =>$a=4,
           ]);

       }

    }
    public function click_month(){
        if(cache('click_month')){
            $click=cache('click_month');
        }else{
            $click=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','click_month','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('click_month desc')
                ->limit(10)
                ->select();
            cache('click_month',$click,3600);
        }

      if(!$click){
          return 1;
      }else{
          return $this->fetch('',[
              'click'  =>$click,
              'a'      =>$a=4,
          ]);
      }

    }

    public function click_total(){
        if(cache('click_total')){
            $click=cache('click_total');
        }else{
            $click=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','click_total','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('click_total desc')
                ->limit(10)
                ->select();
            cache('click_total',$click,3600);
        }

        if(!$click){
            return 1;
        }else{
            return $this->fetch('',[
                'click'  =>$click,
                'a'      =>$a=4,
            ]);
        }

    }
    public function collection_weeks(){
           if(cache('collection_weeks')){
               $collection=cache('collection_weeks');
           }else{
               $collection=Db::view('Book','book_id,book_name,author_name,upload_img')
                   ->view('BookStatistical','collection_weeks','BookStatistical.book_id=Book.book_id')
                   ->view('BookType','book_type','BookType.type_id=Book.type_id')
                   ->where(['is_show'=>1,'audit'=>1])
                   ->order('collection_weeks desc')
                   ->limit(10)
                   ->select();
               cache('collection_weeks',$collection,3600);
           }

        if(!$collection){
            return 1;
        }else{
            return $this->fetch('',[
                'collection'  =>$collection,
                'b'      =>$b=4,
            ]);
        }

    }
    public function collection_month(){
        if(cache('collection_month')){
            $collection=cache('collection_month');
        }else{

            $collection=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','collection_month','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('collection_month desc')
                ->limit(10)
                ->select();
            cache('collection_month',$collection,3600);
        }

        if(!$collection){
            return 1;
        }else{
            return $this->fetch('',[
                'collection'  =>$collection,
                'b'      =>$b=4,
            ]);
        }

    }

    public function collection_total(){
        if(cache('collection_total')){
            $collection=cache('collection_total');
        }else{
            $collection=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','collection_total','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('collection_total desc')
                ->limit(10)
                ->select();
            cache('collection_total',$collection,3600);
        }

        if(!$collection){
            return 1;
        }else{
            return $this->fetch('',[
                'collection'  =>$collection,
                'b'      =>$b=4,
            ]);
        }

    }

    public function buy_weeks(){

         if(cache('buy_weeks')){
             $buy=cache('buy_weeks');
         }else{
             $buy=Db::view('Book','book_id,book_name,author_name,upload_img')
                 ->view('BookStatistical','buy_weeks','BookStatistical.book_id=Book.book_id')
                 ->view('BookType','book_type','BookType.type_id=Book.type_id')
                 ->where(['is_show'=>1,'audit'=>1])
                 ->order('buy_weeks desc')
                 ->limit(10)
                 ->select();
             cache('buy_weeks',$buy,3600);
         }
        if(!$buy){
            return 1;
        }else{
            return $this->fetch('',[
                'buy'  =>$buy,
                'c'      =>$c=4,
            ]);
        }


    }
    public function buy_month(){
        if(cache('buy_month')){
            $buy=cache('buy_month');
        }else{
            $buy=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','buy_month','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('buy_month desc')
                ->limit(10)
                ->select();
            cache('buy_month',$buy,3600);
        }
        if(!$buy){
            return 1;
        }else{
            return $this->fetch('',[
                'buy'  =>$buy,
                'c'      =>$c=4,
            ]);
        }


    }

    public function buy_total(){
        if(cache('buy_total')){
            $buy=cache('buy_total');
        }else{
            $buy=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','buy_total','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('buy_total desc')
                ->limit(10)
                ->select();
            cache('buy_total',$buy,3600);
        }
        if(!$buy){
            return 1;
        }else{
            return $this->fetch('',[
                'buy'  =>$buy,
                'c'      =>$c=4,
            ]);
        }


    }

    public function vote_weeks(){
       if(cache('vote_weeks')){
           $vote=cache('vote_weeks');
       }else{
           $vote=Db::view('Book','book_id,book_name,author_name,upload_img')
               ->view('BookStatistical','vote_weeks','BookStatistical.book_id=Book.book_id')
               ->view('BookType','book_type','BookType.type_id=Book.type_id')
               ->where(['is_show'=>1,'audit'=>1])
               ->order('vote_weeks desc')
               ->limit(10)
               ->select();
           cache('vote_weeks',$vote,3600);
       }
       if(!$vote){
           return 1;
       }else{
           return $this->fetch('',[
               'vote'   =>$vote,
               'd'       =>$d=4
           ]);
       }

    }
    public function vote_month(){
        if(cache('vote_month')){
            $vote=cache('vote_month');
        }else{
            $vote=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','vote_month','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('vote_month desc')
                ->limit(10)
                ->select();
            cache('vote_month',$vote,3600);
        }
        if(!$vote){
            return 1;
        }else{
            return $this->fetch('',[
                'vote'   =>$vote,
                'd'       =>$d=4
            ]);
        }

    }

    public function vote_total(){
        if(cache('vote_total')){
            $vote=cache('vote_total');
        }else{
            $vote=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','vote_total','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('vote_total desc')
                ->limit(10)
                ->select();
            cache('vote_total',$vote,3600);
        }
        if(!$vote){
            return 1;
        }else{
            return $this->fetch('',[
                'vote'   =>$vote,
                'd'       =>$d=4
            ]);
        }

    }

    public function count_weeks(){
        if(cache('count_weeks')){
            $count=cache('count_weeks');
        }else{
            $count=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','count_weeks','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('count_weeks desc')
                ->limit(10)
                ->select();
            cache('count_weeks',$count,3600);
        }
        if(!$count){
            return 1;
        }else{
            return $this->fetch('',[

                'count'   =>$count,
                'e'     =>$e=4
            ]);
        }

    }
    public function count_month(){
        if(cache('count_month')){
            $count=cache('count_month');
        }else{
            $count=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','count_month','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('count_month desc')
                ->limit(10)
                ->select();
            cache('count_month',$count,3600);
        }
        if(!$count){
            return 1;
        }else{
            return $this->fetch('',[

                'count'   =>$count,
                'e'     =>$e=4
            ]);
        }

    }

    public function count_total(){
        if(cache('count_total')){
            $count=cache('count_total');
        }else{
            $count=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','count_total','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('count_total desc')
                ->limit(10)
                ->select();
            cache('count_total',$count,3600);
        }
        if(!$count){
            return 1;
        }else{
            return $this->fetch('',[

                'count'   =>$count,
                'e'     =>$e=4
            ]);
        }

    }
    public function vipvote_month(){
        if(cache('vipvote_weeks')){
            $vipvote=cache('vipvote_weeks');

        }else{
            $vipvote=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','vipvote_month','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('vipvote_month desc')
                ->limit(10)
                ->select();
            cache('vipvote_weeks',$vipvote,3600);
        }
        if(!$vipvote){
            return 1;
        }else{
            return $this->fetch('',[
               'vipvote'   =>$vipvote,
               'g'          =>$g=4
            ]);
        }

    }
    public function vipvote_total(){
        if(cache('vipvote_total')){
            $vipvote=cache('vipvote_total');

        }else{
            $vipvote=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','vipvote_total','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('vipvote_total desc')
                ->limit(10)
                ->select();
            cache('vipvote_total',$vipvote,3600);
        }
        if(!$vipvote){
            return 1;
        }else{
            return $this->fetch('',[
                'vipvote'   =>$vipvote,
                'g'          =>$g=4
            ]);
        }

    }
    public function t_click_weeks(){
       if(cache('t_click_weeks')){
           $tongren=cache('t_click_weeks');
       }else{
           $tongren=Db::view('Book','book_id,book_name,author_name,upload_img')
               ->view('BookStatistical','click_weeks','BookStatistical.book_id=Book.book_id')
               ->view('BookType','book_type','BookType.type_id=Book.type_id')
               ->where(['is_show'=>1,'audit'=>1,'Book.type_id'=>16])
               ->order('click_weeks desc')
               ->limit(10)
               ->select();
           cache('t_click_weeks',$tongren,3600);
       }
        if(!$tongren){
           return 1;
        }else{
           return $this->fetch('',[
               'tongren'     =>$tongren,
               'h'            =>$h=4
           ]);
        }
    }
    public function t_click_month(){
        if(cache('t_click_month')){
            $tongren=cache('t_click_month');
        }else{
            $tongren=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','click_month','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1,'Book.type_id'=>16])
                ->order('click_month desc')
                ->limit(10)
                ->select();
            cache('t_click_month',$tongren,3600);
        }
        if(!$tongren){
            return 1;
        }else{
            return $this->fetch('',[
                'tongren'     =>$tongren,
                'h'            =>$h=4
            ]);
        }
    }
    public function t_click_total(){
        if(cache('t_click_total')){
            $tongren=cache('t_click_total');
        }else{
            $tongren=Db::view('Book','book_id,book_name,author_name,upload_img')
                ->view('BookStatistical','click_total','BookStatistical.book_id=Book.book_id')
                ->view('BookType','book_type','BookType.type_id=Book.type_id')
                ->where(['is_show'=>1,'audit'=>1,'Book.type_id'=>16])
                ->order('click_total desc')
                ->limit(10)
                ->select();
            cache('t_click_total',$tongren,3600);
        }
        if(!$tongren){
            return 1;
        }else{
            return $this->fetch('',[
                'tongren'     =>$tongren,
                'h'            =>$h=4
            ]);
        }
    }
}