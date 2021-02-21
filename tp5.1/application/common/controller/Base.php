<?php
namespace app\common\controller;
use think\Controller;
use think\facade\Session;
use app\common\model\Artcate;
use app\admin\common\model\Site;
use think\facade\Request;
use app\common\model\Article;
/**
 * 公共控制器,必须继承think\Controller.php,这样模块中的控制器继承base.php即可
 */
class Base extends Controller
{
	
	//初始化，公共方法，在所有方法调用前被调用
	protected function initialize()
    {
       //显示首页的分类导航
	     $this->showNav();

       //检测站点是否关闭
       $this->enter();

       //首页热门浏览排行
       $this->getHotArt();
    }
    
    //判断是否重复登录
    protected function isLogin(){
    	   
  	        if(Session::has('user_id')){
  	        	$this->error('当前已经登录请勿重复登录','index/index/index');
  	        }
    }

    //判断是否已经登录
     protected function isLogined(){
        
            if(!Session::has('user_id')){
              $this->error('您好,是不是忘记登录啦！','index/user/login');
            }
    }

    //显示导航页的分类信息
    protected function showNav(){

          //用闭包查询,获取所有分类信息
          $catelist=Artcate::all(function($query){
          $query->where('status',1)->order('sort','asc');
          });
          //halt($catelist);
          //分配给nav.html模板
          $this->assign('catelist',$catelist);
    }

    //如果站点关闭，禁止访问前台，但是后台还是可以访问
    public function enter(){
          
          //获取站点信息
          $isOpen=Site::where(['status'=>1])->value('is_open');
          //halt($isOpen);

          //判断
          if($isOpen==0 && Request::module()=='index'){
           
            $info="<div><h1>站点维护中,敬请关注...</h1></div>";
              
            exit($info);
        }  
    }   

    //注册维护,当站点信息中is_reg为0时候，不允许注册
    public  function isRegist(){
          //获取站点信息
           $isReg=Site::where('status',1)->value('is_reg');
           if($isReg==0 && Request::module()=='index'){
               $this->error('注册功能暂时关闭','index/index');
           }
    } 

    //首页热门排行,按阅读量进行显示前五条
    public  function getHotArt(){
          //从文章模型中获取
          $hotlist=Article::where('status',1)
          ->order('pv','desc')
          ->limit(12)
          ->select();
          //halt($hotlist);
          //模板赋值
          $this->assign('hotlist',$hotlist);

    }
}