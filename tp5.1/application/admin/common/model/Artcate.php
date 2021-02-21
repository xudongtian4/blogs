<?php
namespace app\admin\common\model;
use think\Model;
/**
 * 
 */
class Artcate extends Model
{
	
	protected $pk='id';
	protected $autoWriteTimestamp=true;
	protected $createTime='create_time';
	protected $updateTime='update_time';
}