<?php
namespace app\common\model;
use think\Model;
/**
 * 
 */
class User extends Model
{
	  protected $pk='id';         //申明主键

	  protected $autoWriteTimestamp=true;
	  protected $createTime='create_time';
	  protected $updateTime='update_time';
	  protected $dateFormat='Y年m日d日';    //设置显示时间

	  //获取器
	  public function getStatusAttr($value){
	  	   $status=['1'=>'启用','0'=>'禁用'];
	  	   return $status[$value];
	  }

      /*public function getIsAdminAttr($value){
	  	   $status=['1'=>'管理员','0'=>'普通用户'];
	  	   return $status[$value];
	  }*/
      
      //修改器
      
      
      public function setPasswordAttr($value)
    {
        return MD5($value);
    }
}
