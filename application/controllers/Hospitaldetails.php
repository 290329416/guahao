<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

class HospitaldetailsController extends BaseController {
    /**
     * 初始化
     */
    function init() {
        parent::init();
    }

    /**
     * 医院详情页
     */
    public function indexAction($hospitalId) {
    	$hospitalId = htmlspecialchars($hospitalId);
		$doctorItems = array(1=>'主任医师',2=>'主治医师',3=>'副主任医师',4=>'医师',5=>'其他');
		$academicItems = array(1=>'教授',2=>'副教授',3=>'其他');
		$effect = array(0=>'不知道',1=>'非常不满意',2=>'不满意',3=>'一般',4=>'满意',5=>'很满意');
		$attitude = array(1=>'非常不满意',2=>'不满意',3=>'一般',4=>'满意',5=>'很满意');
		//获取医院信息
		$hospital = parent::get_curl(array(
		'url'=>'hospital/detail',
		'id'=>$hospitalId));				//医院ID
		
		//就医分享
		$share = parent::get_curl(array(
		'url'=>'extcoop/comments/exphosp/id',
		'type'=>1,
		'id'=>$hospitalId));
		
		//本院医生
		$expert = parent::get_curl(array(
		'url'=>'expert/info',
		'hospitalId'=>$hospitalId,
		'currentPage'=>1,
		'pageSize'=>4));
		
		//按科室查找
		$keshi = parent::get_curl(array(
		'url'=>'department/info',
		'hospitalId'=>$hospitalId,
		'currentPage'=>1,
		'pageSize'=>1000));
		$keshid = $keshi['departments']['department'];
		$ksnum = count($keshid);
		$kslist =array();
		for($i=0;$i<$ksnum;$i++){
			if(!array_key_exists($keshid[$i]['normDepartName'],$kslist)){
				$kslist[$keshid[$i]['normDepartName']] = array(array('hdeptId'=>$keshid[$i]['hdeptId'],'name'=>$keshid[$i]['name'],'expertCount'=>$keshid[$i]['expertCount']));
			}else{
				array_push($kslist[$keshid[$i]['normDepartName']],array('hdeptId'=>$keshid[$i]['hdeptId'],'name'=>$keshid[$i]['name'],'expertCount'=>$keshid[$i]['expertCount']));
			}
		}
		
		//相关文章
		$article = file_get_contents("http://manager.ruanwen.haomeit.com/jiekou/index?hospitalid=$hospitalId");
		if(!empty($article)){
			$this->getView()->assign('article',$article);
		}
		//医院信息数据
		$this->getView()->assign('hospital',$hospital['hospital']);
		
		//找科室
		$this->getView()->assign('kslist',$kslist);
		
		//就医分享数据
		$this->getView()->assign('share',$share['comments']['comment']);
		$this->getView()->assign('effect',$effect);
		$this->getView()->assign('attitude',$attitude);
		
		//本院医生
		$this->getView()->assign('expert',$expert['experts']['expert']);
		$this->getView()->assign('doctorItems',$doctorItems);
		$this->getView()->assign('academicItems',$academicItems);
    }
	/**
     * 医院相关文章页
     */
    public function newsAction($hospitalId,$newsid) {
    	$hospitalId = htmlspecialchars($hospitalId);
    	$newsid = htmlspecialchars($newsid);
    	$doctorItems = array(1=>'主任医师',2=>'主治医师',3=>'副主任医师',4=>'医师',5=>'其他');
		$academicItems = array(1=>'教授',2=>'副教授',3=>'其他');
    	//获取医院信息
		$hospital = parent::get_curl(array(
		'url'=>'hospital/detail',
		'id'=>$hospitalId));

		//本院医生
		$expert = parent::get_curl(array(
		'url'=>'expert/info',
		'hospitalId'=>$hospitalId,
		'currentPage'=>1,
		'pageSize'=>2));

		//医院相关文章
		$news = file_get_contents("http://manager.ruanwen.haomeit.com/jiekou/souhucontent?articleid=$newsid");
		$news =json_decode($news,true);
		
		//医院信息数据
		$this->getView()->assign('hospital',$hospital['hospital']);
		//本院医生
		$this->getView()->assign('expert',$expert['experts']['expert']);
		$this->getView()->assign('doctorItems',$doctorItems);
		$this->getView()->assign('academicItems',$academicItems);
		//医院相关文章
		$this->getView()->assign('news',$news);
    }
}
