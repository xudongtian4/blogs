<?php
namespace app\admin\controller;
use app\admin\common\controller\Base;
use think\facade\Session;
use app\admin\common\model\User;


class Index extends Base {

	  public function index(){

		  	 //进入后台首页先判断是登录
	         $this->isLogin();
		  	 return $this->redirect('/admin/user/userlist'); //公共控制器中的跳转方法
	  }

	  
}
