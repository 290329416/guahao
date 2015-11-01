<?php

/**
 * 首页控制类
 *
 * User: zhaoqingyang@lianchuangbrothers.com
 * Date: 15-7-20
 * Time: 12:52
 */
if (!defined('APP_PATH'))
    exit('No direct script access allowed');

class DoctorController extends BaseController {

    function init() {
        parent::init();
    }
	
    function indexAction() {
		if(!$_GET['key']){
			$_GET['key'] = '内科';
		}


		$urlmd5 = md5($_SERVER['REQUEST_URI']);
		$doctor = json_decode($this->redis->get($urlmd5),true);

		if($doctor == false){
					$doctor = parent::get_curl(array(
					'url'=>'/extcoop/department/search/expert',
					'name'=>htmlspecialchars($_GET['key']),						//科室名称
					'sort'=>1,		
					'hospitalLevel'=>htmlspecialchars($_GET['hospitalLevel']),	//医院等级
					'expertTitle'=>htmlspecialchars($_GET['expertTitle']),		//专家职称
					'provinceId'=>htmlspecialchars($_GET['cityId']),			//城市ID
					'currentPage'=>htmlspecialchars($_GET['page']),
					'pageSize'=>12));
			$this->redis->set($urlmd5, json_encode($doctor), 3600);
		}
		
		
		$npage = strpos($_SERVER['REQUEST_URI'],'page');

		if($npage){
			$newurl = ltrim(substr($_SERVER['REQUEST_URI'],0,$npage-1),'/');
		}else{
			$newurl = ltrim($_SERVER['REQUEST_URI'],'/');
		}

		if($newurl == 'doctor'){
			$newurl = 'doctor?did=7f640bba-cff3-11e1-831f-5cf9dd2e7135&key=内科&cityId=&hospitalLevel=&expertTitle=';
		}
		if($_GET['page'] && $_GET['page']<=$doctor['totalPage'] && $_GET['page']>0){
			$num = $_GET['page'];
		}elseif($_GET['page']>=$doctor['totalPage']-5){
			$num = $doctor['totalPage']-5;
		}elseif($_GET['page']<=0){
			$num = 1;
		}

		$page = "<a href='".BASEURL.$newurl."&page=1"."'>首页</a>";
		for($i=($num-4<=0?1:$num-4);$i<=($num+4<=8?8:$num+4);$i++){
			if($i<=$doctor['totalPage']){
			if($i==$num){
				$page .= "<a href='".BASEURL.$newurl."&page=".$i."'><font color='#FF0000'><b>".$i."</b></font></a>";
			}else{
				$page .= "<a href='".BASEURL.$newurl."&page=".$i."'>".$i."</a>";
			}
			if($i==($num+4<=8?8:$num+4)){
				$page .= "<a href='".BASEURL.$newurl."&page=".$doctor['totalPage']."'>尾页</a>";
			}
			}
		}
		$page .= "  {$doctor['currentPage']}/{$doctor['totalPage']}";
		$this->getView()->assign('doctor',$doctor['experts']['expert']);
		$this->getView()->assign('key',$_GET['key']);
		$this->getView()->assign('cityId',$_GET['cityId']);
		$this->getView()->assign('hospitalLevel',$_GET['hospitalLevel']);
		$this->getView()->assign('expertTitle',$_GET['expertTitle']);
		$this->getView()->assign('page',$page);
    }
}
