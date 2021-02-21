<?php
use app\common\model\User;
use app\common\model\Artcate;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//通过用户id获取用户名称
if(! function_exists('getUserName')){
	function getUserName($id){
    return User::where('id',$id)->value('name');
    }
}

//过滤文章摘要
if(! function_exists('getArtContent')){
      function getArtContent($content){
        return mb_substr(strip_tags($content),0,30).'.....详情';
      }
}

//根据分类id,获取分类名称
if(! function_exists('getCateName')){
	function getCateName($id){
    return Artcate::where('id',$id)->value('name');
    }
}

