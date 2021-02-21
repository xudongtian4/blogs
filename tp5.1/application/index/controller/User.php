<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;
/**
 * 继承公共控制器
 */
class User extends Base
{      
	  //渲染用户注册页面
	  public function  register(){
            
            //先检测网站是否允许注册
	  	    $this->isRegist();

	  	    $this->assign('title','用户注册');
	  	    return $this->fetch();
	  }
	  
	  //用户数据入库
	  public function insert(){
             //判断提交数据的类型
	         if(Request::isAjax()){
                    //接收数据
                    $data=Request::post();  //要验证的数据
		         	
	                $data1=Request::except('password_confirm','post');//过滤字段
                     
                    //引入验证规则
                    $rule='app\common\validate\User';

                    //开始验证
                    $res=$this->validate($data,$rule);

                    if(true !== $res){
                    	return ['status'=>-1,'message'=>$res];
                    }else{ 
                    	    //用户注册成功,实现自动登录
	                        if($user=UserModel::create($data)){
	                        	$res=UserModel::get($user->id);
                                Session::set('user_id',$res->id);
                                Session::set('user_name',$res->name);
		                         return ['status'=>1,'message'=>'恭喜您注册成功！'];
			                 }else{
			                 	 return ['status'=>0,'message'=>'很遗憾,注册失败！'];
			                 }
                     }
		                 
	          }else{
	         	 
	         	 return $this->error('请求类型错误','login');
	        }
	  }
  
      //渲染用户登录页面
	  public function login(){
	  	        //先判断是否登录
                $this->isLogin();
                
			  	$this->view->assign('title','用户登录');
			    return	$this->view->fetch();
	  }

      //用户登录验证
	  public function  loginCheck(){
            //判断提交数据的类型
	         if(Request::isAjax()){
                    //接收数据
                     $data=Request::post();  //要验证的数据
		         	
                    //引入验证规则
                    $rule=[
                         'email'=>'require|email',
                         'password|密码'=>'require|alphaNum',
                    ];

                    //开始验证
                    $res=$this->validate($data,$rule);

                    if(true !== $res){
                    	return ['status'=>-1,'message'=>$res];
                    }else{ 
                             //执行查询操作
                    	     $res=UserModel::get(function($query) use($data){
                    	     	$query->where('email',$data['email'])
                    	     	->where('password',md5($data['password']));
                    	     });
 
	                        if(null == $res){
	                        	return ['status'=>0,'message'=>'用户名或密码错误！'];
			                 }else{
                                 //将数据存入到session中
                                  Session::set('user_id',$res->id);
                                  Session::set('user_name',$res->name);
 
                                  Session::set('admin_id',$res->id);
		  	                      Session::set('admin_name',$res->name);
                                  Session::set('admin_level',$res->is_admin);
                                  
			                 	 return ['status'=>1,'message'=>'恭喜登录成功！'];
			                 }
                        }
		                 
	          }else{
	         	 
	         	 return $this->error('请求类型错误','register');
	        }
	  }

	  //退出登录功能
	  public function logout(){
	  	     //退出登录两种方法：
	  	     // Session::delete('user_name');
	  	     // Session::delete('user_id');

	  	     Session::clear();

	  	     $this->success('退出登录成功','index/index');
	  }
}