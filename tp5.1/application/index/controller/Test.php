<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\common\model\User;
//
/**专门用于测试用
 * 
 */
class Test extends Base
{
	 //测试用户的验证器
	 public function test(){

	 	dump(User::get(48));
	 }
	
}