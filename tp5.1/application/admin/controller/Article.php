<?php
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Article as ArtModel;
use app\admin\common\model\Artcate;
use think\facade\Request;
use think\facade\Session;
use think\Db;
/**
 * 
 */
class Article extends Base
{   

	public function index(){
             //进入后台首页先判断是登录
	         $this->isLogin();
		  	 return $this->redirect('/admin/article/artlist'); //公共控制器中的跳转方法
	    
	}

    //渲染分类管理的模板
	public  function artList(){

             //进入后台首页先判断是登录
	         $this->isLogin();
             
             //设置权限,判断用户id和用户级别
             $userId=Session::get('user_id');
             $userLevel=Session::get('admin_level');
             
            //1.为管理员
	        if($userLevel==1){

                 $artlist=ArtModel::paginate(4);

	        }else{
	        	//2.为普注册用户
	        	 $artlist=ArtModel::where('user_id',$userId)->paginate(4);
	        }


             //分配模板变量
             $this->assign('title','文章管理');
             $this->assign('empty','<span style="color:red">没有相关文章</span>');
             $this->assign('artlist',$artlist);

            return $this->fetch('artlist');
	}

	//编辑文章信息,先渲染模板
	public function artEdit(){
            //获取分类的id信息
		    $artId=Request::param('id');

		    //获取文章分类信息
		    $catelist=Artcate::all();

		    //获取当前文章的信息
		    $artInfo=ArtModel::where('id',$artId)->find();

		    //模板赋值
		    $this->assign('catelist',$catelist);
		    $this->assign('title','编辑文章');
		    $this->assign('artInfo',$artInfo);

		    //渲染编辑分类的模板
		    return $this->fetch('artedit');
	}
	public function artUpdate(){
	           //获取要编辑的栏目的id
			   $artId=Request::param('id');
              
			   //接收提交的信息
			   $data=Request::param(); //halt($data);

               //验证成功,获取图片信息,$file是一个文件对象
			   $file=Request::file('title_img');  //halt($file);
               
			   //先查询要编辑的栏目的信息
			   $info=Db::table('zh_article')->where('id',$artId)->find();

               $dif=array_diff_assoc($data, $info); //halt($dif);
               

               //判断数据是否变化
               if(! is_null($file)){
               	    //文件上传成功后上传到指定的目录中,以public为根目录$info也是对象
                  $info=$file->validate([
                      'size'=>2000000,
                      'ext' =>'jpeg,jpg,png,gif',
                  ])->rule('uniqid')
                  ->move('uploads/');  //halt($info);

                  //文件信息正确后的处理
                  if($info){
                       $data['title_img']=$info->getSaveName();

                       $res=ArtModel::update($data);//有id可以直接更新,自动完成
                       //$res=ArtModel::where('id',$artId)->update($data);//也可以
				   	  if($res){
				   	  	 $this->success('更新成功','artList');

				   	  }else{
				   	  	 $this->error('更新失败,请检查');
				   	  }
                       
                  }else{
                       $this->error($file->getError());
                  }

             }else{
                   if(empty($dif)){
                   	  $this->error('数据未做更新');
                   }else{
                        $res=ArtModel::update($data);
				   	  if($res){
				   	  	 $this->success('更新成功','artList');

				   	  }else{
				   	  	 $this->error('更新失败,请检查');
                   }
               }
          }
	}
	
	//删除分类信息
	public function artDelete(){
		       //获取要编辑的栏目的id
			   $artId=Request::param('id');

         	   $res=ArtModel::where('id',$artId)->delete();
         	   
         	   if($res){
         	   	   $this->success('删除成功','artList');
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