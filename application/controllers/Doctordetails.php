<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

class DoctordetailsController extends BaseController {
    /**
     * 初始化
     */
    function init() {
        parent::init();
    }

    /**
     * 医生详情页
     */
    public function indexAction($hospitalId, $deptId, $id) {
    	$hospitalId = htmlspecialchars($hospitalId);
    	$deptId = htmlspecialchars($deptId);
    	$id = htmlspecialchars($id);
		$doctorItems = array(1=>'主任医师',2=>'主治医师',3=>'副主任医师',4=>'医师',5=>'其他');
		$academicItems = array(1=>'教授',2=>'副教授',3=>'其他');
		$effect = array(0=>'不知道',1=>'非常不满意',2=>'不满意',3=>'一般',4=>'满意',5=>'很满意');
		$attitude = array(1=>'非常不满意',2=>'不满意',3=>'一般',4=>'满意',5=>'很满意');
		//获取医生信息
		$doctor = parent::get_curl(array(
		'url'=>'expert/info',
		'hospitalId'=>$hospitalId,					//医院ID
		'hdeptId'=>$deptId,		
		'expertId'=>$id));
		
		//预约规则
		$rule = parent::get_curl(array(
		'url'=>'hospital/detail',
		'id'=>$hospitalId));					//医院ID
		
		//就医分享
		$share = parent::get_curl(array(
		'url'=>'extcoop/comments/exphosp/id',
		'type'=>2,
		'id'=>$id));
		
		//同科室专家
		$expert = parent::get_curl(array(
		'url'=>'expert/info',
		'hospitalId'=>$hospitalId,
		'hdeptId'=>$deptId,
		'currentPage'=>1,
		'pageSize'=>5));

		//医生信息数据
		$this->getView()->assign('doctor',$doctor['experts']['expert']);
		$this->getView()->assign('doctorItems',$doctorItems);
		$this->getView()->assign('academicItems',$academicItems);
		$this->getView()->assign('hospitalId',$hospitalId);
		$this->getView()->assign('deptId',$deptId);
		$this->getView()->assign('id',$id);
		$this->getView()->assign('doctor',$doctor['experts']['expert']);
		
		//预约规则数据
		$this->getView()->assign('rule',$rule['hospital']);
		
		//就医分享数据
		$this->getView()->assign('share',$share['comments']['comment']);
		$this->getView()->assign('effect',$effect);
		$this->getView()->assign('attitude',$attitude);
		
		//同科室专家
		$this->getView()->assign('expert',$expert['experts']['expert']);
    }
	
}
