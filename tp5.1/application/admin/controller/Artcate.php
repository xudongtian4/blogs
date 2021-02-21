<?php
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Artcate as CateModel;
use app\admin\common\model\Article;
use think\facade\Request;
use think\facade\Session;
use think\Db;
/**
 * 
 */
class Artcate extends Base
{   

	public function index(){
             //进入后台首页先判断是登录
	         $this->isLogin();
		  	 return $this->redirect('/admin/artcate/catelist'); //公共控制器中的跳转方法
	    
	}

    //渲染分类管理的模板
	public  function cateList(){

             //进入后台首页先判断是登录
	         $this->isLogin();

	         //获取分类信息,select()和all()方法得到的是数组对象,才可以进行遍历
	         //分类的对象数组 $catelist=CateModel::all();
	         $catelist=CateModel::all(function($query){

	         	$query->order('create_time');
	         });  //分类的对象数组 $catelist=CateModel::all();
             
             //分配模板
             $this->assign('title','分类管理');
             $this->assign('empty','<span style="color:red">没有分类信息</span>');
             $this->assign('catelist',$catelist);

            return $this->fetch('catelist');
	}

	//编辑分类信息,先渲染模板
	public function cateEdit(){
            //获取分类的id信息
		    $cateId=Request::param('id');

		    //获取分类的信息
		    $cateInfo=CateModel::where('id',$cateId)->find();

		    //模板赋值
		    $this->assign('title','编辑分类');
		    $this->assign('cateInfo',$cateInfo);

		    //渲染编辑分类的模板
		    return $this->fetch('cateedit');
	}
	public function cateUpdate(){
	           //获取要编辑的栏目的id
			   $cateId=Request::param('id');

			   //接收提交的信息
			   $data=Request::param(); 

			   //先查询要编辑的栏目的信息
			   $info=Db::table('zh_artcate')->where('id',$cateId)->find();
	          
			   //如果数据未做更新
			   if(empty(array_diff_assoc($data, $info))){
	              $this->error('信息未做更新！');
			   }else{
				   	  $res=CateModel::where('id',$cateId)->update($data);
				   	  if($res){
				   	  	 $this->success('更新成功','cateList');
				   	  }else{
				   	  	 $this->error('更新失败,请检查');
				   	  }
			   }

	}
	
	//删除分类信息
	public function cateDelete(){
		         //获取要编辑的栏目的id
				 $cateId=Request::param('id');

				 //获取栏目信息
				 $cateInfo=Article::where('cate_id',$cateId)->find();
		         if(! is_null($cateInfo)){
		         	 $this->error('当前栏目下有文章,不能删除！');
		         }else{
		         	   $res=CateModel::where('id',$cateId)->delete();
		         	   
		         	   if($res){
		         	   	$this->success('删除成功','cateList');
		         	   }else{
		         	   	$this->error('删除失败,请检查');
		         	   }
		         }

	} 

	//渲染添加分类的页面
	public function cateAdd(){

		    return $this->fetch('cateadd',['title'=>'添加分类']);
	}

	//确认提交分类
	public function cateInsert(){

		   //获取提交的数据
		   $data=Request::param();

		   //新增
		   $res=CateModel::create($data);

		   if($res){
		   	   	$this->success('新增成功','cateList');
		   }else{
		   	    $this->error('删除失败,请检查');
		   }
	}

	//实现批量删除
	public function delete(){
          /*$id=Request::param('id');
          halt($id);*/

          $data = $_POST['checkID'];
          
          $DB = new Db;
          $res=$DB::table("goods")->delete($data);
          if($res){
            echo"ok";
          }
	} 
}