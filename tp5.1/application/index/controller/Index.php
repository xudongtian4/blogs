<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\common\model\Artcate;
use think\facade\Request;
use think\facade\Session;
use app\common\model\Article;
use app\common\model\Comments;

use think\Db;

class Index extends Base
{   
	//渲染主页模板
    public function index()
    {       
            //获取全局查询条件1
            $map[]=['status','=',1];

            $keywords=Request::get('keywords');

            if(! empty($keywords)){

            //查询条件2
            $map[]=['title','like','%'.$keywords.'%'];
            }
            //显示分类信息
            $cateId=Request::param('cate_id');

            if(isset($cateId)){

            //查询条件3
            $map[]=['cate_id','=',$cateId];

            $cate=Artcate::get($cateId);

            $this->view->assign('cateName', $cate->name); 

            //闭包查询
            /*$art=Article::all(function($query) use($cateId){
              $query->where('status',1)->where('cate_id',$cateId)
              ->order('create_time','desc')->paginate(5);
            });*/

            //模型查询
            $art=Article::where($map)
            ->order('create_time','desc')
            ->paginate(3);
           
            }else{   

            $art=Article::where($map)
            ->order('create_time','desc')
            ->paginate(3);

            $this->assign('cateName', "全部帖子");
            }
            //如果没有查询到
            $this->assign('empty','<h3>没有文章</h3>');
            $this->view->assign('article',$art);

            return $this->fetch();
    }

    //渲染发布文章的模板
    public function insert(){

    	    	//用户登录才可以发布
    	    	$this->isLogined();
                
            //获取文章栏目信息
            $catelist=Artcate::all();
        
            if(count($catelist)>0){
            //将查询到的信息赋给模板
            $this->assign('catelist',$catelist);
	            }else{
	                 $this->error('请先添加分类','index/index');
	            }
    	    	$this->assign('title','发布文章');
    	    	return $this->fetch();
	  }

    //发布的文章的入库
    public function save(){
           //判断数据提交方式
           if(Request::isPost()){
            //接收数据
            $data=Request::post(); //halt($data);

            //验证规则
            $rule='app\common\validate\Article';
            //执行数据验证
            $res=$this->validate($data,$rule);

            if(true !== $res){

            echo '<script>alert("'.$res.'");window.location.back()</script>';
                  
            }else{
                  //验证成功,获取图片信息,$file是一个文件对象
                  $file=Request::file('title_img'); //halt($file);
                  
                  if(is_null($file)){
                      $this->error('请上传图片');
                  }
                  //文件上传成功后上传到指定的目录中,以public为根目录$info也是对象
                  $info=$file->validate([
                      'size'=>2000000,
                      'ext' =>'jpeg,jpg,png,gif',
                  ])->rule('uniqid')
                  ->move('uploads/');  //halt($info);

                  //文件信息正确后的处理
                  if($info){
                       $data['title_img']=$info->getSaveName();//上传到服务器上的名字
                       //halt($data);
                  }else{
                       $this->error($file->getError());
                    }

                  //保存到数据库
                  //$article=new Article();

                  if(Article::create($data)){
                      $this->success('文件上传成功！','index/index');
                  }else{
                      $this->error('文件上传失败！');
                  }
            }
    }else{
       $this->error('数据提交方式不正确！');
        }  
    }

    //获取文章详情信息
    public function detial(){
                
               $art_id=Request::param('art_id'); //获取文章id

               $art=Article::get(function($query) use($art_id){

                $query->where('id',$art_id)->setInc('pv');
               });

               if(! is_null($art)){
                $this->view->assign('art',$art);
               }
               
               //获取当前文章的评论
               $commentlist=Comments::where('status',1)->where('art_id',$art_id)->order('create_time','desc')->select();

               $count=count($commentlist);
               
               //模板赋值
               $this->assign('count',$count);
               $this->assign('commentlist',$commentlist);
               $this->assign('empty','<span style="color:red">没有评论!</span>');
               $this->assign('title','页面详情');
               return $this->view->fetch();
    }
    
    //将发表的评论插入数据库
    public function commentInsert(){
            
            //先检测用户是否登录
             if(!Session::has('user_id')){
                  return ['status'=>-1,'message'=>'您是不是忘记登录了,请先登录'];
            }

            //获取评论的数据
            $data=Request::get();
            
            if(Request::isAjax()){
            //将数据添加到数据库
            $res=Comments::create($data);

            if($res){
               return ['status'=>1,'message'=>'发表评论成功！'];

            }else{
               return ['status'=>0,'message'=>'评论失败,请稍后再试！'];
            }

         }
    } 
    //收藏
    public function fav(){

             if(!Request::isAjax()){
               return ['status'=>-1,'message'=>'请求类型不正确！'];
             }

             //获取提交的数据
             $data=Request::param(); //halt($data);
             if (empty($data['session_id'])) {
                 return ['status'=>-2,'message'=>'请先登录再收藏！'];
             }
            
             //查询条件
             $map[]=['user_id','=',$data['user_id']];
             $map[]=['art_id','=',$data['art_id']];
             $res=Db::table('zh_fav')->where($map)->find();
             //halt($res);
            
             if(! null==$res){
                 Db::table('zh_fav')->where($map)->delete();
                 return ['status'=>0,'message'=>'已取消！'];
             }else{
                 //过滤多余字段插入
                 Db::table('zh_fav')->strict(false)->insert($data); 
                 return ['status'=>1,'message'=>'收藏成功！'];
             }
    }

    //点赞
    public function ok(){

             if(!Request::isAjax()){
               return ['status'=>-1,'message'=>'请求类型不正确！'];
             }

             //获取提交的数据
             $data=Request::param(); //halt($data);
             if (empty($data['session_id'])) {
                 return ['status'=>-2,'message'=>'请先登录再点赞！'];
             }
            
             //查询条件
             $map[]=['user_id','=',$data['user_id']];
             $map[]=['art_id','=',$data['art_id']];
             $res=Db::table('zh_like')->where($map)->find();
             //halt($res);
            
             if(! null==$res){
                 Db::table('zh_like')->where($map)->delete();
                 return ['status'=>0,'message'=>'已取消！'];
             }else{
                 //过滤多余字段插入
                 Db::table('zh_like')->strict(false)->insert($data); 
                 return ['status'=>1,'message'=>'点赞成功！'];
             }
    }

     
    public function getComment(){
              //获取文章id
              $art_id=Request::param('art_id');

              //获取当前文章下的评论列表
              $commentlist=Comments::where('status',1)->where('art_id',$art_id)->order('create_time','desc')->select();

              //获取评论数量
             $count=count($commentlist);
             //模板变量赋值

             $this->assign('count',$count);
             $this->assign('empty','<span style="color:red">没有评论!</span>');
             $this->assign('commentlist',$commentlist);
             //渲染评论列表
             return $this->fetch();
    } 
    
    //返回回复信息的模板
    public function reply(){

             return $this->fetch();
    }

    //删除评论信息
    public function delete(){
            $id=Request::param('id');
            if(isset($id)){
              $res=Comments::where('id',$id)->delete();
                if($res){
                   return ['statua'=>1,"message"=>'删除成功'];
                }else{
                   return ['statua'=>0,"message"=>'删除失败']; 
                }
            } 
    }
}
