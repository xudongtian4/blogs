<?php
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;
use think\Db;

/**
 * 
 */
class User  extends Base
{   
    //用户首页
    public function index(){

    }

	//渲染管理员登录页面
	public  function login(){
	       $this->view->assign('title','管理员登录');
	       return $this->view->fetch();
	}

	//校验用户登录信息
	public function checklogin(){

	           //接收数据
			  $data= Request::param();  //halt($data);
			  
			  //查询条件
			  $map[]=['email','=',$data['email']];
			  $map[]=['password','=',md5($data['password'])];

			  $res=UserModel::where($map)->find();
	         
			  if($res){
			  	   //匹配到,先保存到session中
			  	   Session::set('admin_id',$res['id']);
			  	   Session::set('admin_name',$res['name']);
			  	   Session::set('admin_level',$res['is_admin']);

			  	   Session::set('user_id',$res['id']);  //保存前台的session的值
	               Session::set('user_name',$res['name']);

	               $this->success('登陆成功','admin/user/userlist');
			  }else{
	               $this->error('用户名或密码错误！');
			  }

	}

	//管理员退出功能
	public  function logout(){
		      //清除session
		      Session::clear(); //cleal方法不是静态方法
		      $this->success('退出成功','admin/user/login');

	}

	//渲染用户列表页
	  public function userlist(){
            
	  	    $data['admin_id']=Session::get('admin_id');
	  	    $data['admin_level']=Session::get('admin_level');

             
             //用户如果是管理员,可以看到所有信息
             if($data['admin_level']==1){

             	$userlist=UserModel::paginate(2);//UserModel::select()也可以查询所有
             }else{
             	//获取用户信息
                $userlist=UserModel::where('id',$data['admin_id'])->paginate(2); 
             }
             //halt($userlist);

            $this->assign('title','用户管理');
            $this->assign('empty','<span style="color:red">没有任何数据<span>');
            $this->assign('userlist',$userlist);
            return $this->view->fetch();
 
	  }

	  //渲染用户编辑页面
	  public function userEdit(){
	  	    //获取用户主键
	  	    $userId=Request::param('id');

	  	    //通过主键获取用户信息
	  	    $userInfo=UserModel::where('id',$userId)->find();
	  	    
            //halt($userInfo);

            $this->assign('userInfo',$userInfo);
	  	    $this->assign('title','用户编辑');
	  	    return $this->fetch();
	  }
	
	  public function userDelete(){
	  	    //获取请求的数据
            $userId=Request::param('id');

            //$res=UserModel::destroy($userId); //返回受影响的行数

            $res=UserModel::where('id',$userId)->delete(); 
            //halt($res);//返回受影响的行数
            
            if($res){
            	$this->success('删除成功！','user/userlist');
            }
	  }

	  public function userUpdate(){

		  	//获取用户提交的信息
		  	$data=Request::param(); //halt($data

		  	//获取主键id
		  	$id=$data['id'];
            
            //数据库查询原数据信息
		  	$res=Db::table('zh_user')->where('id',$id)->find(); 
            
            //判断数据是否修改
	        $data1=array_diff_assoc($data, $res);//halt($data1);
            
            if(empty($data1)){
            	$this->error('数据未做更新！');
            }else{
                  
                if($data['password']!=$res['password']){
            	//此时密码修改过,重新进行密码加密
	            $data['password']=md5($data['password']);
                }

            	$res=UserModel::where('id',$id)->update($data); //返回受影响的行数
            
                   if($res){
            	      $this->success('更新成功！','user/userlist');
                  }else{
		    	     $this->error('更新失败,请检查！');
		          }

           }
            //删除主键id
	        // unset($data['id']);

		    /*$res=UserModel::update($data);   //返回当前放模型对象
		    halt($res);
		    if($res){
		    	$this->success('更新成功！','user/userlist');
		    }else{
		    	$this->error('更新失败！');
		    }*/
	 }
}