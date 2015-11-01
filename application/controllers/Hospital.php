<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

class HospitalController extends BaseController {
    /**
     * 初始化
     */
    function init() {
        parent::init();
    }

/**
* 文章列表页
* dir----dirname
*/
public function IndexAction($key) {
	$num = preg_match_all('/(or|and|exec|insert|select|delete|update|count|chr|mid|master|truncate|char|declare)/U',$key);
	if($num>0){
		exit("非法地址!");
	}
	$key = htmlspecialchars($key);
	if(empty($key)){
		$key = 'neike';
	}
	$Hospital = new HospitalModel();
	$department_one = $Hospital->department_one[$key];
	if(empty($department_one)){
		$department_two = $Hospital->department_two[$key];
	}

	if(!empty($department_two)){
		$urlmd5 = md5($_SERVER['REQUEST_URI']);
		$hospital = json_decode($this->redis->get($urlmd5),true);
		if($hospital == false){
			$hospital = parent::get_curl(array(
			'url'=>'extcoop/disease/search/hospital',
			'name'=>$department_two['department'],			//疾病名称
			'provinceId'=>$_GET['provinceId'],				//省份
			'sort'=>1,										//排序
			'currentPage'=>$_GET['page'],					//第几页
			'hospitalLevel'=>$_GET['hospitalLevel'],		//医院等级
			'pageSize'=>12));

			$this->redis->set($urlmd5, json_encode($hospital), 3600);
		}
		
		//获取相应科室下的疾病库
		/*$keshi = parent::get_curl(array(
		'url'=>'extcoop/department/disease',
		'id'=>$department_two['did']));*/
	}
	if(!empty($department_one)){
		$urlmd5 = md5($_SERVER['REQUEST_URI']);
		$hospital = json_decode($this->redis->get($urlmd5),true);
		if($hospital == false){
			$hospital = parent::get_curl(array(
			'url'=>'extcoop/department/search/hospital',
			'name'=>$department_one['department'],			//科室名称
			'provinceId'=>$_GET['provinceId'],				//省份
			'hospitalLevel'=>$_GET['hospitalLevel'],		//医院等级
			'currentPage'=>$_GET['page'],					//第几页
			'sort'=>1,										//排序
			'pageSize'=>12));

			$this->redis->set($urlmd5, json_encode($hospital), 3600);
		}
		
		//获取相应科室下的疾病库
		/*$keshi = parent::get_curl(array(
		'url'=>'extcoop/department/disease',
		'id'=>$department_one['did']));*/
	}
	$npage = strpos($_SERVER['REQUEST_URI'],'page');
	if($npage){
		$newurl = ltrim(substr($_SERVER['REQUEST_URI'],0,$npage-1),'/');
	}else{
		$newurl = ltrim($_SERVER['REQUEST_URI'],'/');
	}
	if($newurl == "yy-{$key}/"){
		$p = '?page';
	}else{
		$p = '&page';
	}

	if($_GET['page'] && $_GET['page']<$hospital['totalPage']){
		$num = $_GET['page'];
	}elseif($_GET['page']>=$hospital['totalPage']){
		$num = $hospital['totalPage'];
	}else{
		$num = 1;
	}


	$page = "<a href='".BASEURL.$newurl.$p."=1"."'>首页</a>";
	for($i=$num-2<=0?1:$num-2;$i<=($num+2<=5?5:$num+2);$i++){
		if($i<=$hospital['totalPage']){
			if($i==$num){
				$page .= "<a href='".BASEURL.$newurl.$p."=".$i."'><font color='#FF0000'><b>".$i."</b></font></a>";
			}else{
				$page .= "<a href='".BASEURL.$newurl.$p."=".$i."'>".$i."</a>";
			}
			if($i==($num+2<=5?5:$num+2)){
				$page .= "<a href='".BASEURL.$newurl.$p."=".$hospital['totalPage']."'>尾页</a>";
			}
		}
	}
	$page .= "  {$hospital['currentPage']}/{$hospital['totalPage']}";
	
	if(!empty($department_one)){
		$this->getView()->assign('title',$department_one);
	}else{
		$this->getView()->assign('title',$department_two);
	}
	$this->getView()->assign('hospital',$hospital['hospitals']['hospital']);
	$this->getView()->assign('disease',$keshi['diseases']['disease']);
	$this->getView()->assign('department',$department_one['department']);
	$this->getView()->assign('page',$page);
	$this->getView()->assign('key',$_GET['key']);
	$this->getView()->assign('did',$_GET['did']);
	$this->getView()->assign('provinceId',$_GET['provinceId']);
	$this->getView()->assign('hospitalLevel',$_GET['hospitalLevel']);
}

/*首页地区找医院ajax*/
public function listAction() {
	if(!empty($_POST['provinceId'])){
		$hospital = parent::get_curl(array(
		'url'=>'extcoop/disease/search/hospital',
		'name'=>'',
		'provinceId'=>$_POST['provinceId'],
		'sort'=>1,
		'pageSize'=>8));
	}
	$list = '';
	for($i=0;$i<8;$i++){
		$list .="<li><a href='/h_{$hospital['hospitals']['hospital'][$i]['id']}/'>{$hospital['hospitals']['hospital'][$i]['name']}</a></li>";
	}
	echo $list;
	exit;
	}
}
