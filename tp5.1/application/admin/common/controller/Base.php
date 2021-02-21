<?php

namespace app\admin\common\controller;
use think\Controller;
use think\facade\Session;
/**
 * 
 */
class Base extends Controller
{
	
	protected  function initialize(){

	}

	//判断是否登录
	protected function isLogin(){
		if (! Session::has('admin_id')) {
			$this->error('请先登录','/admin/user/login');
		}

	}
}
