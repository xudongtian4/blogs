<?php
namespace app\common\validate;
use think\Validate;
/**
 * 验证zh_user数据表的字段验证器
 */
class Artcate extends Validate
{
	protected $rule=[
    
                'name|标题'  =>'require|length:3,20|chsAlpha',
           
	];

}