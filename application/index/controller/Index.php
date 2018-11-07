<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Index extends Controller
{
    public function index()
    {
       // print_r($_SERVER['DOCUMENT_ROOT']);exit();
        $Cache =\think\Loader::controller('gongju/Cache');
        $Cache->chushi("/Html_Cache/index/", 'index');
        $Cache->read_cache(); //读取缓存
        $slide= $this->slide();
        $godTouch =$this->god_touch();//深夜神触
        $push=$this->push();//主编强推
        $hot=$this->hot();//火热连载
        $dachu=$this->dachu();//大触神作
        $xinzuo=$this->xinzuo();//潜力新作
        $newBook =$this->newBook();//新书预热
        $chapter =$this->newChapter();//最新章节
        $click =$this->echo_click();//点击榜
        $collection =$this->echo_collection();//收藏榜
        $vote =$this->echo_vote();//推荐榜
        $tongren =$this->echo_tongren();//同人榜
       // $this->fetch();
       // $Cache->create_cache(); //生成缓存
       // print_r($tongren);exit();
        return $this->fetch('',[
            'slide'  =>$slide,
            'godTouch' =>$godTouch,
            'push'    =>$push,
            'hot'     =>$hot,
            'dachu'   =>$dachu,
            'xinzuo'  =>$xinzuo,
            'newBook'  =>$newBook,
            'chapter'  =>$chapter,
            'a'        =>$a=1,
            'click'    =>$click,
            'b'        =>$b=4,
            'collection'  =>$collection,
            'c'      =>$c=4,
            'vote'   =>$vote,
            'd'     =>$d=4,
            'tongren'  =>$tongren,
            'e'   =>$e=4,
            'ok'    =>$ok=1
        ]);



     }

     /*
      * 首页轮播图
      * $promote_id=15
      */
    public function slide(){
         $slide =Db::name('BookPromote')->where(['promote_id'=>15])
                   ->field('book_id,upload_img')->order('xu asc')->limit(5)->select();
         return $slide;
    }
    /*
     * 深夜神触
     * $promote_id=3
     */
    public function god_touch(){
        $godTouch =Db::view('BookPromote','book_id,book_name,upload_img')
                   ->view('Book','level','Book.book_id=BookPromote.book_id','LEFT')
                   ->where(['promote_id'=>3])
                   ->order('xu asc')
                   ->limit(12)
                   ->select();
        return $godTouch;
    }
    /*
     * 主编强推
     * $promote_id=4
     */
    public function push(){
        $push =Db::view('BookPromote','book_id,book_name,upload_img,book_brief')
            ->view('Book','level,words,author_name','Book.book_id=BookPromote.book_id','LEFT')
            ->view('BookType','book_type','BookType.type_id=Book.type_id','LEFT')
            ->where(['promote_id'=>4])
            ->order('xu asc')
            ->limit(12)
            ->select();
        return $push;

    }
    /*
     * 火热连载
     * $promote_id=5
     */
    public function hot(){
        $hot =Db::view('BookPromote','book_id,book_name,upload_img,book_brief')
            ->view('Book','level,words,author_name','Book.book_id=BookPromote.book_id','LEFT')
            ->view('BookType','book_type','BookType.type_id=Book.type_id','LEFT')
            ->where(['promote_id'=>5])
            ->order('xu asc')
            ->limit(12)
            ->select();
        return $hot;

    }
    /*
     * 大触神作
     * $promote=6
     */
    public function dachu(){
        $dachu =Db::view('BookPromote','book_id,book_name,upload_img,book_brief')
            ->view('Book','level,words,author_name','Book.book_id=BookPromote.book_id','LEFT')
            ->view('BookType','book_type','BookType.type_id=Book.type_id','LEFT')
            ->where(['promote_id'=>6])
            ->order('xu asc')
            ->limit(12)
            ->select();
        return $dachu;

    }
    /*
     * 潜力新作
     * $promote_id=7
     */
    public function xinzuo(){
        $xinzuo =Db::view('BookPromote','book_id,book_name,upload_img,book_brief')
            ->view('Book','level,words,author_name','Book.book_id=BookPromote.book_id','LEFT')
            ->view('BookType','book_type','BookType.type_id=Book.type_id','LEFT')
            ->where(['promote_id'=>7])
            ->order('xu asc')
            ->limit(6)
            ->select();
        return $xinzuo;
    }
    /*
     * 新书预热
     * $promote_id=8
     */
    public function newBook(){
        $newBook =Db::view('BookPromote','book_id,book_name,upload_img,book_brief')
            ->view('Book','level,words,author_name','Book.book_id=BookPromote.book_id','LEFT')
            ->view('BookType','book_type','BookType.type_id=Book.type_id','LEFT')
            ->where(['promote_id'=>8])
            ->order('xu asc')
            ->limit(6)
            ->select();
        return $newBook;

    }
    /*
     * 最新章节
     */
    public function newChapter(){

        $chapter=Db::view('Book','book_id,book_name,words,author_name')
                ->view('Content','title,time,the_price','Content.book_id=Book.book_id and Content.num=Book.chapter','LEFT')
                ->view('BookType','book_type','BookType.type_id=Book.type_id','LEFT')
                ->where(['is_show'=>1,'audit'=>1])
                ->order('Content.time desc')
                ->limit(20)
                ->select();
        return $chapter;

    }
    /*
     * 点击榜
     * 周榜
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
     * 收藏榜
     * 周榜
     */

    public function echo_collection(){
        $collection=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','click_weeks','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('collection_weeks desc')
            ->limit(10)
            ->select();
        return $collection;
    }
    /*
     * 推荐榜
     * 周榜
     */
    public function echo_vote(){

        $vote=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','click_weeks','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id')
            ->where(['is_show'=>1,'audit'=>1])
            ->order('vote_weeks desc')
            ->limit(10)
            ->select();
        return $vote;
    }

    /*
     * 同人榜
     */
    public function echo_tongren(){
        $tongren=Db::view('Book','book_id,book_name,author_name,upload_img')
            ->view('BookStatistical','click_weeks','BookStatistical.book_id=Book.book_id')
            ->view('BookType','book_type','BookType.type_id=Book.type_id','LEFT')
            ->where(['is_show'=>1,'audit'=>1,'Book.type_id'=>16])
            ->order('click_weeks desc')
            ->limit(10)
            ->select();
        return $tongren;
    }
}
