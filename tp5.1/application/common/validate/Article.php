<?php
namespace app\common\validate;
use think\Validate;
/**
 * 验证zh_user数据表的字段验证器
 */
class Article extends Validate
{
	protected $rule=[
    
               'title|标题'    =>'require|length:5,40|chsAlphaNum',
           'content|文章内容'   =>'require',
           'user_id|作者'       =>'require',
           'cate_id|栏目名称'   =>'require',
           //'title_img|标题图片' => 'require',
	];

}