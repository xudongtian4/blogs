<?php
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Site as SiteModel;
use think\facade\Request;
use think\facade\Session;
use think\Db;

/**
 * 
 */
class Site extends Base
{    

	 //渲染站点模板首页
	 public function index(){
	 	  //获取网站信息
	 	  $siteInfo=SiteModel::get(['status'=>1]);

	 	  //模板赋值
	 	  $this->assign('siteInfo',$siteInfo);
	 	  return $this->fetch('index');
	 }

	 //站点信息更新方法
	 public function siteUpdate(){
	 	  //获取跟新数据
	 	  $data=Request::param();
          
          //获取原信息
          $info=Db::table('zh_site')->where('id',$data['id'])->find();

          $dif=array_diff_assoc($data, $info);
	 	  
	 	  if(empty($dif)){
	 	  	  $this->error('数据未做更新');
	 	  }else{
              if(SiteModel::update($data)){
              	   $this->success('更新成功','index');
              }else{

              	   $this->error('更新失败,请检查');
              }
	 	  }

	 }
}