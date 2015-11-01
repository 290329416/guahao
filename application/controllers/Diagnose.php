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

class DiagnoseController extends BaseController {

    function init() {
        parent::init();
    }

    function testAction() {
    	$_GET['id'] = htmlspecialchars($_GET['id']);
		$this->getView()->assign('id',$_GET['id']);
	    
    }

    function csAction(){
	if($_POST){
			$cs_a = parent::get_curl(array(
			'url'=>'extcoop/diagnose/next',
			'labelId'=>'201091172350531',
			'id'=>"{$_POST['id']}",
			'isSelect'=>0));
			print_r(json_encode('{id:'.$cs_a['diagnose']['id'].',content:'.$cs_a['diagnose']['content'].'}'));exit;
		}
		$cs = parent::get_curl(array(
		'url'=>'extcoop/symptom/diagnose',
		'labelId'=>'201091172350531',
		'id'=>"{$_GET['id']}"));
		//echo "<pre>";
		//print_r($cs);
		//exit;
		$this->getView()->assign('csdiagnose',$cs['diagnose']);
		$this->getView()->assign('cssymptom',$cs['symptom']);
    }
}