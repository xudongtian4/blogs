<?php
namespace app\common\model;
use think\Model;
/**
 * 
 */
class Comments extends Model
{
	  protected $pk='id';
	  protected $autoWriteTimestamp=true;
	  protected $createTime='create_time';
	  protected $updateTime='update_time';
	  protected $dateFormat='Y-m-d H-i';


	 //开启自动设置，不论是新增还是更新时都有效
	 protected $auto=[];  
     
     //新增时有效
	 protected $insert=['create_time','status'=>1];

	 //更新时有效
	 protected $update=['update_time'];

}