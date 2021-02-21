<?php
namespace app\common\model;
use think\Model;
/**
 * 
 */
class Article  extends Model
{
	  protected $pk='id';         //申明主键

	  protected $autoWriteTimestamp=true;
	  protected $createTime='create_time';
	  protected $updateTime='update_time';
	  protected $dateFormat='Y年m日d日';    //设置显示时间

	 
	 //开启自动设置，不论是新增还是更新时都有效
	 protected $auto=[];  
     
     //新增时有效
	 protected $insert=['create_time','status'=>1,'is_top'=>0,'is_hot'=>0];

	 //更新时有效
	 protected $update=['update_time'];
}
