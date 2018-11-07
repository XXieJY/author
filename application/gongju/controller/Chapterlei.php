<?php
namespace app\gongju\controller;
use app\author\controller\Redis;
use think\Controller;
use think\Db;
use think\Request;
class Chapterlei extends Controller{

    //新增章节
    public function add($data,$bookid,$price){


        Db::startTrans();//开启事务

        $book=Db::name('Book');
        $conn=Db::name('Content');
       //获取当前卷
      $juan=  $conn->where(['book_id'=>$bookid,'title'=>$data['roll']])->find();
      //根据当前卷获取当前最大章节坐标
       $zuobiao= $conn->field('num')->where(['book_id'=>$bookid,'volume_fid'=>$juan['volume_id']])->order('num desc')->find();
       if(is_array($zuobiao)){
         $myzb=$zuobiao['num']+1;
       }else{
           $myzb=$juan['num']+1;
       }

        //更新坐标
        $map['book_id']=$bookid;
        $map['num']    =array('EGT', $myzb); //查找比该坐标大的数据
        $newzb=$conn->where($map)->field('content_id')->order('num asc')->select();
        if(is_array($newzb)){
            $xinzb=$myzb+1;
            for ($i=0;$i<count($newzb);$i++){
                $conn->where(['content_id'=>$newzb[$i]['content_id']])->update(['num'=>$xinzb]);
                $xinzb++;
            }
        }

      //数据入库
        $con['book_id'] = $bookid;
        $con['num'] = $myzb;
        $con['title'] = trim($data['chapter']);
        $con['number'] = $data['number'];
        $con['word'] = $data['number'];
        if($con['number']<1000){
            $con['the_price']=0;
        }else{
            $con['the_price'] = ceil($con['number'] / 1000 * $price);
        }
        $con['price']=$price;
        $con['state']=1;
        $con['volume_fid']=$juan['volume_id'];
        $con['time'] = date('Y-m-d H:i:s', time());
        if($data['time']){
            $con['update_time']=$data['time'];
            $con['status']=1;
        }else{
            $con['update_time']=date('Y-m-d H:i:s');
            $con['status']=0;
        }

        $cid = $conn->insert($con);
        $content_id=$conn->getLastInsID();
        //插入内容
        $neirongs['content_id'] = $content_id;
        $neirongs['content'] = $data['txt'];
        $neirongs['msg']   =$data['msg'];
        $neirongs['time'] =date('Y-m-d H:i:s');

       try{
          $re1= Db::name('Contents')->insert($neirongs);
           //更新字数
           $words=$con['number'];
           if(!$data['time']){
              $numArr= $conn->where(['book_id'=>$bookid,'type'=>0])->order('num desc')->find();
               $datas['chapter'] = $numArr['num']; //总数多少章
               $datas['words'] = array('exp', "words+$words");
               $aaa=$book->where(['book_id'=>$bookid])->find();
               if($aaa['vip']==0){
                   if( $con['the_price']>0){
                       $datas['vip']=1;
                       $datas['vip_time']=date('Y-m-d H:i:s');
                   }
               }
           }
           $datas['update_time']=date('Y-m-d H:i:s');
           $re2=  $book->where(array('book_id' => $bookid))->update($datas);
           //更新统计字数
           if(!$data['time']){

               $st['count_day']=array('exp',"count_day+$words");
               $st['count_weeks']=array('exp',"count_weeks+$words");
               $st['count_month']=array('exp',"count_month+$words");
               $st['count_total']=array('exp',"count_total+$words");
               $re3=  Db::name('BookStatistical')->where(['book_id'=>$bookid])->update($st);
           }

           if(!$data['time']){

               if($re1 && $re2 && $re3){
                   Db::commit();//提交事务
                   return 1;
               }

           }else{
               if($re1 && $re2){
                   Db::commit();//提交事务
                   return 1;
               }

           }


       }catch (\Exception $e){
           Db::rollback();//回滚事务
           print_r($e->getMessage());
           exit();
       }




    }
   //删除章节
    public function delete($connkid){
        Db::startTrans();//开启事务
        $content = Db::name('Content');
        $book = Db::name('Book');
        //查看该章节是否存在
        $conn = $content->where(array('content_id' => $connkid))->find();
        if (!is_array($conn)) {
            $this->error("章节不存在");
            exit();
        }
        try{
            //删除章节
          $re1=  $content->where(array('content_id' => $connkid))->delete();
           $re2=      Db::name('Contents')->where(array('content_id' => $connkid))->delete();
            //更新坐标
            $map['book_id'] = $conn['book_id'];
            $map['num'] = array('GT', $conn['num']); //查找比该坐标大的数据
            $gengxinid = $content->where($map)->field('content_id')->order('num ASC')->select();
            if (is_array($gengxinid)) {
                $xinzb = $conn['num'];
                for ($i = 0; $i < count($gengxinid); $i++) {
                    $content->where(array('content_id' => $gengxinid[$i]['content_id']))->update(array('num' => $xinzb));
                    $xinzb++;
                }
            }
            //更新作品
            $words=$conn['number'];
            $bata['words'] = array('exp', "words-$words");
            $bata['chapter'] = array('exp', "chapter-1");
           $re3= $book->where(array('book_id' => $conn['book_id']))->update($bata);
            //更新统计字数
            $st['count_day']=array('exp',"count_day-$words");
            $st['count_weeks']=array('exp',"count_weeks-$words");
            $st['count_month']=array('exp',"count_month-$words");
            $st['count_total']=array('exp',"count_total-$words");
            $re4=  Db::name('BookStatistical')->where(['book_id'=>$conn['book_id']])->update($st);
            if($re1 && $re2 && $re3 && $re4){

                Db::commit();//提交事务
                return 1;
            }

        }catch (\Exception $e){
            Db::rollback();//回滚事务
        }




    }

    public function shangchuan($name, $book_id, $xuhao,$volume_id,$price) {

        //准备工作
        $book = Db::name('Book');
        $con = Db::name('Content');
        $cons = Db::name('Contents');
        $id = 0; //ID号
        $content = NULL; //内容
        $zongzishu = 0; //总字数
        $nums = 1; //时间
        $fp = @fopen('./Upload/text/'.$name,'r');

        if ($fp){
            while (!feof($fp)){
                $bruce = fgets($fp);
                $title = strstr($bruce, "###");
                // echo $title;exit();
                if ($title) {
                    if ($id) {
                        $number = $this->trimall($content);
                        if($number<1000){
                            $the_price=0;
                        }else{
                            $the_price = ceil($number / 1000 * $price);
                        }
                        $con->where(array('content_id' => $id))->update(array('number' => $number, 'the_price' => $the_price,'price'=>$price));
                        //插入内容
                        $keyword= Db::name('SystemKeyword')->select();
                        foreach ($keyword as $k=>$v){
                            $content=str_replace($v,"**",$content);
                        }
                        $neirongs['content_id'] = $id;
                        $neirongs['content'] = trim($content);

                        //    $neirongs['content']=mb_convert_encoding ( $neirongs['content'], 'UTF-8','GB2312');
                        Db::name('Contents')->insert($neirongs);
                        $zongzishu = $zongzishu + $number;
                        $content = NULL;
                    }

                    $xinzb= Db::name('Content')->where(['book_id'=>$book_id,'num'=>['egt',$xuhao]])->select();
                    if(is_array($xinzb)){
                            $aaa=$xuhao+1;
                            for ($i=0;$i<count($xinzb);$i++){
                                Db::name('Content')->where(['content_id'=>$xinzb[$i]['content_id']])->update(['num'=>$aaa]);
                                $aaa++;
                            }
                    }

                    $title = str_replace("###", "", trim($title));
                    //  $title =  mb_convert_encoding ( $title, 'UTF-8','GB2312');
                    //  echo $encode;exit();
                    //准备内同数据
                    $data['book_id'] = $book_id;
                    $data['num'] = $xuhao;
                    $data['title'] = trim($title);
                    $data['price']=$price;
                    $data['volume_fid']=$volume_id;
                    $data['time'] = date('Y-m-d H:i:s', time());
                    $data['update_time'] = date('Y-m-d H:i:s', time()); //发布时间
                    // var_dump($data);exit();
                    $con->insert($data); //添加作品章节内容
                    $id =$con->getLastInsID();

                    $nums = $nums + 3;
                    $xuhao++;


                }else {
                    $content = $content.$bruce;
                    //dump($content);exit;
                }
            }
            $number = $this->trimall($content);
            if($number<1000){
                $the_price=0;
            }else{
                $the_price = ceil($number / 1000 *$price);
            }
            $con->where(array('content_id' => $id))->update(array('number' => $number, 'the_price' => $the_price,'price'=>$price));
            //插入内容
            $keyword= Db::name('SystemKeyword')->select();
            foreach ($keyword as $k=>$v){
                $content=str_replace($v,"**",$content);
            }

            $neirongs['content_id'] = $id;
            $neirongs['content'] = trim($content);

            //   $neirongs['content'] =  mb_convert_encoding ( $neirongs['content'], 'UTF-8','GB2312');
            $res = $cons->insert($neirongs);
            //echo $cons->getLastSql();
            //echo $res;exit;
            $zongzishu = $zongzishu + $number;
            //$content = NULL;
            //更新书籍表格
         if($price>0){
                 $datas['vip'] =1;
                 $datas['vip_time']  =date('Y-m-d H:i:s');
         }
            $chaptering = $con->where(array('book_id' => $book_id,'type'=>0))->field('num')->order('num desc')->find();
            $datas['chapter']=$chaptering['num'];
            $datas['words'] = array('exp', "words+$zongzishu");
            $datas['update_time'] =date('Y-m-d H:i:s');
            $book->where(array('book_id' => $book_id))->update($datas);
            //更新书籍统计表
            $st['count_day']=array('exp',"count_day+$zongzishu");
            $st['count_weeks']=array('exp',"count_weeks+$zongzishu");
            $st['count_month']=array('exp',"count_month+$zongzishu");
            $st['count_total']=array('exp',"count_total+$zongzishu");
             Db::name('BookStatistical')->where(['book_id'=>$book_id])->update($st);
            //生成txt内容文件
            //file_put_contents("Upload/Book/".$book_id."/txt/".$id.".txt",$content);

        }else {
            echo "打开失败:Upload/text/$name";
        }
        $this->success("上传成功");

    }

    //修改章节
    public function save($data){
       Db::startTrans();//开启事务
       $book =Db::name('Book');
       $content =Db::name('Content');
       $stat=Db::name('BookStatistical');
       try{
           $chapter =$content->where(['content_id'=>$data['contentId']])->find();
           if(!is_array($chapter)){
               $this->error('系统错误');
           }
           //用户在修改章节的时候先清除该章节在Redis里的缓存
           $redis =Redis::getRedisConn();
           $redisKey = REDIS_CHAPTER_CONTENT_INDEX.$chapter['book_id'].'_' .$chapter['num'];

           $pcRedisKey = REDIS_CHAPTER_CONTENT_INDEX_PC.$chapter['book_id']."_".$chapter['num'];
           $redis->set($redisKey,null);
           $redis->set($pcRedisKey,null);
           $words =$chapter['number'];
           //更新书籍字数统计1.本周 2.本月
           if($chapter['status']!=1){

               //判断本周
               $testWeek_start=strtotime($chapter['time']);
               $testWeek_end=strtotime(date('Y-m-d H:i:s'));
               $flag= $this->getSameWeek($testWeek_start,$testWeek_end)?true:false;
               if($flag){
                   $st['count_weeks']=array('exp',"count_weeks-$words");
               }
               //判断本月
              if(strtotime(date('Y-m'))==strtotime(date('Y-m'),strtotime($chapter['time']))) {
                  $st['count_month']=array('exp',"count_month-$words");
              }

               $b_data['words']=array('exp',"words-$words");
               $re1= $book->where(['book_id'=>$data['bookid']])->update($b_data);
               //更新统计字数
               $st['count_total']=array('exp',"count_total-$words");
               $re2= $stat->where(['book_id'=>$data['bookid']])->update($st);
           }

           $con['title'] = trim($data['chapter']);
           $con['number'] = $data['number'];
           if($con['number']<1000){
               $con['the_price']=0;
           }else{
               $con['the_price'] = ceil($con['number'] / 1000 * $data['price']);
           }
           $con['price']=$data['price'];
           if($chapter['status']==1){//章节本身属于定时发布状态可以修改发布时间
               if($data['time']){

                   $con['update_time']=$data['time'];
               }else{
                   $con['time'] =date('Y-m-d H:i:s');
               }
           }else{
               $con['update_time'] =date('Y-m-d H:i:s');
           }



          //修改章节
          $re3= $content->where(['content_id'=>$data['contentId']])->update($con);
         //修改内容
           $neirongs['content'] = $data['txt'];
           $neirongs['msg']   =$data['msg'];
           $neirongs['time'] =date('Y-m-d H:i:s');
          $re4= Db::name('Contents')->where(['content_id'=>$data['contentId']])->update($neirongs);
           //更新字数
           $word=$con['number'];

           if($chapter['status']!=1) {
               //判断本周
               $testWeek_start = strtotime($chapter['time']);
               $testWeek_end = strtotime(date('Y-m-d H:i:s'));
               $flag = $this->getSameWeek($testWeek_start, $testWeek_end) ? true : false;
               if ($flag) {
                   $sts['count_weeks'] = array('exp', "count_weeks+$word");
               }
               //判断本月
               if (strtotime(date('Y-m')) == strtotime(date('Y-m'), strtotime($chapter['time']))) {
                   $sts['count_month'] = array('exp', "count_month+$word");
               }

               $datas['words'] = array('exp', "words+$word");
               $datas['update_time'] =date('Y-m-d H:i:s');
               $re5= $book->where(array('book_id' => $data['bookid']))->update($datas);
               //更新统计字数
               $sts['count_total']=array('exp',"count_total+$word");
               $re6= $stat->where(['book_id'=>$data['bookid']])->update($sts);

           }
           if($chapter['status']!=1) {

               if($re1 && $re2 && $re3 && $re4 && $re5 && $re6){
                   Db::commit();//提交事务
                   return 1;
               }

           }else{
               if($re3 && $re4){
                   cache('shudong_chapter_dingshi',null);
                   Db::commit();//提交事务
                   return 1;
               }

           }

       }catch (\Exception $e){
           Db::rollback();//回滚事务
           print_r($e->getMessage());exit();
       }



    }

    //提交草稿箱
    public function update($data){
        Db::startTrans();//开启事务
        //修改书籍章节状态
        try{
            $con['title']    =trim($data['chapter']);
            $con['number'] =$data['number'];
            $con['word'] = $data['number'];
            if($con['number']<1000){
                $con['the_price']=0;
            }else{
                $con['the_price']=ceil($con['number'] / 1000 * $data['price']);
            }
            $con['price']  =$data['price'];
            $con['time'] =date('Y-m-d H:i:s');
            $con['state']  =1;
                if($data['time']){
                    $con['update_time']=$data['time'];
                    $con['status']  =1;
                }else{
                    $con['update_time'] =date('Y-m-d H:i:s');
                }
           $re1=  Db::name('Content')->where(['content_id'=>$data['contentId']])->update($con);
            //修改内容
            $neirong['content']   =$data['txt'];
            $neirong['msg']       =$data['msg'];
            $neirong['time']     =date('Y-m-d H:i:s');
          $re2=  Db::name('Contents')->where(['content_id'=>$data['contentId']])->update($neirong);
            //更新书籍
            $words =$con['number'];

           $abc= Db::name('Book')->where(['book_id'=>$data['bookid']])->find();
           if($abc['vip']==0){
               if($con['the_price']>0){
                   $b_data['vip'] =1;
                   $b_data['vip_time']  =date('Y-m-d H:i:s');
               }
           }
       //判断是否为定时发布
            if(!$data['time']){
                $b_data['words'] =array('exp',"words+$words");
                $b_data['chapter']  =array('exp',"chapter+1");
            }
            $b_data['update_time']  =date('Y-m-d H:i:s');
           $re3= Db::name('Book')->where(['book_id'=>$data['bookid']])->update($b_data);
            //更新统计
            if(!$data['time']){

                $st['count_day']=array('exp',"count_day+$words");
                $st['count_weeks']=array('exp',"count_weeks+$words");
                $st['count_month']=array('exp',"count_month+$words");
                $st['count_total']=array('exp',"count_total+$words");
                $re4= Db::name('BookStatistical')->where(['book_id'=>$data['bookid']])->update($st);
            }
         if(!$data['time']){
             if($re1 && $re2 && $re3 && $re4 ){
                 Db::commit();//提交事务
                 return 1;
             }

         }else{

             if($re1 && $re2 && $re3 ){
                 Db::commit();//提交事务
                 return 1;
             }
         }


        }catch (\Exception $e){
            Db::rollback();//回滚事务
        }

  }

    //字数统计函数
    public function trimall($str)
    {
        ///删除空格
        $qian = array(" ","\t", "\n", "\r");
        $hou = array("", "", "", "");
        $str = str_replace($qian, $hou, $str);
        $str = mb_convert_encoding($str, 'GBK', 'UTF-8');
        preg_match_all("/[" . chr(0xa1) . "-" . chr(0xff) . "]{2}/", $str, $m);
        $mu = count($m[0]);
        unset($str);
        unset($m);
        return $mu;

    }


    //处理敏感词
    public function sensitive_words($content){

        $str="抽插,抽动,做爱,强奸,迷奸,轮奸,诱奸,奸杀,奸污,群奸,群p,群P,迷幻药,催情药,淫穴,淫水,淫汁,淫叫,淫乱,骚穴,浪穴,蜜穴,嫩穴,骚货,骚妇,肉棒,肉棍,大屌,肉洞,乳房,乳头,咪咪,揉捏,揉胸,抚摸下体,舔胸,援交,阴蒂,敏感点,阴户,阴道,阳具,鸡巴,射精,自慰,手淫,撸管,打飞机,要射了,要泄了,爱液横流,春水泛滥,爱液泛滥,春水横流,顶花心,到花心,胸推,乳交,兽交,口交,性交,乱交,性爱,足交,肛门,肛交,爆菊,操逼,草逼,艹逼,内射,深喉,颜射,内射,裸聊,卖淫,约炮,插屁屁,少年阿宾,少妇白洁,恋童癖,漏阴癖,色情聊,激情裸,性爱视频,成人视频,性爱直播,成人直播,亂倫,乱伦,充气娃娃,振动棒,震动棒,跳蛋,龙根,操嫂子,操了嫂,把精子,SM,sm,做保健,叫床,娇喘,门保健,门按摩,擠乳汁,挤乳汁,奇淫散,黑木耳,粉木耳,酣战连连,香汗靡靡,赤裸着,毛泽东,毛泽西,周恩来,蒋介石,习近平,习远平,习晋平,李克强,薄熙来,王岐山,温家宝,江泽民,林彪,胡锦涛,胡景涛,彭丽媛,回良玉,郭伯雄,徐才厚,吴邦国,李咏曰,李洪志,黎阳平,特朗普,金正恩,赵紫阳,张春桥,北京市,东莞,香港,澳门,台湾,越南,日本,韩国,朝鲜,印度,老挝,美国,俄罗斯,苏联,意大利,加拿大,基督教,伊斯兰教,犹太教,印度教,搞台独,搞传销,法轮功,法伦功,警察殴打,警方包庇,城管打人,共狗,苍蝇水,吸毒,城管打人,警察打人,冰毒,藏獨,上访,藏独,儿园凶,儿园杀,儿园砍,儿园惨,赌球网,安局豪华,被中共,报复执法,仿真枪,麻醉枪,售卖枪支,售卖毒品,买卖枪支,买卖毒品,售信用,售五四,手枪,售三棱,售热武,售枪支,售冒名,售麻醉,售氯胺售猎枪,售军用,售健卫,售假币,艳照门,手机窃,伦理电影,情色小说,情色书籍,情色交易,权色交易,保护伞,中央军委,1040阳光工程,北部湾建设,维卡币,云币,暗黑币,四人帮,傻逼,傻屌,操你妈,草你妈,艹你妈,草尼玛,日你妈,贱货,妈逼,脑残,妈的智障,我操,bitch,他妈的,你他妈,草哭,操哭,艹苦,新疆暴乱,新疆暴动,新疆骚乱,西藏暴乱,西藏暴动,西藏骚乱,东突分子,东突份子,分裂份子,分裂分子,乌鲁木齐7,乌鲁木齐七,七五事件,新疆和田,伊塔事件,文化大革命,中国暴乱,中国暴动,基地分子,巴仁乡暴,暴力恐怖事件,鄯善县骚乱,龙湾事件,六四事,60年代大饥荒,建国门事件,天安门事件,天安门自焚,八六学潮,八九学潮";
        $keyArr=explode(',',$str);
        for ($i = 0; $i < count($keyArr); $i++) {    //根据数组元素数量执行for循环
            //应用substr_count检测文章的标题和内容中是否包含敏感词
            if (substr_count($content, $keyArr[$i]) > 0) {
                $m = $m . $keyArr[$i] .' ';
            }
        }
        return $m;


    }

    //计算是否在同一周
    public  function getSameWeek($pretime,$aftertime){
        $flag = false;//默认不是同一周
        $afweek = date('w',$aftertime);//当前是星期几
        $mintime = $aftertime - $afweek * 3600*24;//一周开始时间
        $maxtime = $aftertime + (7-$afweek)*3600*24;//一周结束时间
        if ( $pretime >= $mintime && $pretime <= $maxtime){//同一周
            $flag = true;
        }
        return $flag;
    }

}