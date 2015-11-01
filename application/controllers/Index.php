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

class IndexController extends BaseController {

    function init() {
        parent::init();
        //实例化首页model层
        // $this->index = new IndexModel();	
    }

    function testAction() {
       $this->getView()->assign('title', $title);
    }

    function indexAction() {

    	$doctorshare_1 = json_decode($this->redis->get(md5('126708969695736')),true);
    	if($doctorshare_1 == false){
    		$doctorshare_1 = parent::get_curl(array('url'=>'extcoop/comments/exphosp/id','id'=>'126708969695736','type'=>2));
    		$this->redis->setex(md5('126708969695736'), 3600, json_encode($doctorshare_1));

    	}

    	$doctorshare_2 = json_decode($this->redis->get(md5('549635cb-897f-4e26-935f-3ee5eb299720')),true);
    	if($doctorshare_2 == false){
    		$doctorshare_2 = parent::get_curl(array('url'=>'extcoop/comments/exphosp/id','id'=>'549635cb-897f-4e26-935f-3ee5eb299720','type'=>2));
    		$this->redis->setex(md5('549635cb-897f-4e26-935f-3ee5eb299720'), 3600 ,json_encode($doctorshare_2));
    	}

    	$doctorshare_3 = json_decode($this->redis->get(md5('80cd15e7-9850-4837-bfb7-1e9d0da1e7b7')),true);
    	if($doctorshare_3 == false){
    		$doctorshare_3 = parent::get_curl(array('url'=>'extcoop/comments/exphosp/id','id'=>'80cd15e7-9850-4837-bfb7-1e9d0da1e7b7','type'=>2));
    		$this->redis->setex(md5('80cd15e7-9850-4837-bfb7-1e9d0da1e7b7'), 3600,json_encode($doctorshare_3));
    	}

    	$doctorshare_4 = json_decode($this->redis->get(md5('52f8846e-c09f-4aab-b87c-8190463cd832')),true);
    	if($doctorshare_4 == false){
    		$doctorshare_4 = parent::get_curl(array('url'=>'extcoop/comments/exphosp/id','id'=>'52f8846e-c09f-4aab-b87c-8190463cd832','type'=>2));
    		$this->redis->setex(md5('52f8846e-c09f-4aab-b87c-8190463cd832'), 3600,json_encode($doctorshare_4));
    	}

    	$doctorshare_5 = json_decode($this->redis->get(md5('1ba52094-faf9-49aa-9a8a-c70f9238e3b6')),true);
    	if($doctorshare_5 == false){
    		$doctorshare_5 = parent::get_curl(array('url'=>'extcoop/comments/exphosp/id','id'=>'1ba52094-faf9-49aa-9a8a-c70f9238e3b6','type'=>2));
    		$this->redis->setex(md5('1ba52094-faf9-49aa-9a8a-c70f9238e3b6'), 3600,json_encode($doctorshare_5));
    	}
	

		if(!empty($_GET['province']) || !empty($_GET['name'])){
			$index = md5($_GET['name'].$_GET['province']);
			$doctor = json_decode($this->redis->get($index),true);
			if($doctor == false){
				$doctor = parent::get_curl(array('url'=>'extcoop/disease/search/expert','name'=>$_GET['name'],'provinceId'=>$_GET['province'],'sort'=>'2','pageSize'=>'3'));
				$this->redis->set($index, 3600 , json_encode($doctor));
			}
		}else{
			$doctor = json_decode($this->redis->get(md5('indexdoctor')),true);
	    	if($doctor == false){
	    		$doctor = parent::get_curl(array('url'=>'extcoop/disease/search/expert','name'=>'','provinceId'=>'1','sort'=>'2','pageSize'=>'3'));
	    		$this->redis->set(md5('indexdoctor'), 3600 , json_encode($doctor));
	    	}
		}
		
		//获取最新一级栏目下的文章
		$object = new catpathModel();
		$idlist = $object->navidlist();
		$nav = $object->navlist('14');
		$list = $object->Article($nav);
		
		$this->getView()->assign('province',$_GET['province']);
		$this->getView()->assign('name',$_GET['name']);
		$this->getView()->assign('doctor',$doctor['experts']['expert']);
		$this->getView()->assign('nav',$nav);
		$this->getView()->assign('list',$list);
		$this->getView()->assign('idlist',$idlist);
		$this->getView()->assign('doctorshare_1',$doctorshare_1['comments']['comment'][0]);
		$this->getView()->assign('doctorshare_2',$doctorshare_2['comments']['comment'][0]);
		$this->getView()->assign('doctorshare_3',$doctorshare_3['comments']['comment'][0]);
		$this->getView()->assign('doctorshare_4',$doctorshare_4['comments']['comment'][0]);
		$this->getView()->assign('doctorshare_5',$doctorshare_5['comments']['comment'][0]);
    }

}
