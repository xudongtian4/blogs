<?php
namespace app\admin\common\model;
use think\Model;
/**
 * 
 */
class Article extends Model
{
	
	protected $pk='id';
	//更新时有效
	 protected $update=['update_time'];
}