<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');
/**
  前台信息类
  @author wangzejun
  @access public
  @package base
 */
Yaf_Loader::import(PATH_APP . 'modules/Admin/controllers/Admin.php');
Yaf_Loader::import(PATH_APP . 'modules/Admin/models/Config.php');

class ConfigController extends AdminController {

    /**
      初始化
      @param void
      @return void
     */
    function init() {
        parent::init();
        $this->referer      = isset($_SERVER["HTTP_REFERER"]) ? str_replace('http://' . $_SERVER['HTTP_HOST'], '', $_SERVER["HTTP_REFERER"]) : '/admin/index/index';
        //db 实例化
        $this->db_config = new ConfigModel();
    }

    /**
      默认页
      @param void
      @return void
     */
    function indexAction() {
        $data = [];
        $page = $this->getRequest()->getQuery('page') ? $this->getRequest()->getQuery('page') : 1;

        //每页显示数
        $offset = 50;
        $start = ($page - 1) * $offset;
        $data['data'] = $this->db_config->config_info_list($start, $offset);
        //总页数
        $total = $this->db_config->config_info_count();
        $totalpage = ceil($total / $offset);

        //超过一页显示分页
        if ($totalpage > 1) {
            $data['page'] = (new multi())->getSubContent('/admin/config/index?page=', '', $totalpage, $page, 9, ' target="_self" ', '', '', 'no');
        }
        $this->getView()->assign('data', $data);
    }

    /**
      添加数据
      @param void
      @return void
     */
    public function addAction() {
        if ($this->getRequest()->isPost()) {
            //内容
            $data = array();
            $data['scope'] = $this->getRequest()->getPost('scope', 0);
            $data['variable'] = $this->getRequest()->getPost('variable', 0);
            $data['value'] = $this->getRequest()->getPost('value', 0);
            $data['description'] = $this->getRequest()->getPost('description', 0);
            if ($this->db_config->add_config($data) !== false) {
                $this->redirect('/admin/config/index');
            } else {
                $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/admin/document/index';
                $this->redirect($referer);
            }
        } else
            $this->getView();
    }
    
    public function deleteAction() {
        $variable = $this->getRequest()->getQuery('variable');
        if ($this->db_config->delete_config(['variable'=>$variable]) !== false) {
             $this->redirect('/admin/config/index');
        }
        exit;
    }
    
    public function updateAction() {
        if ($this->getRequest()->isPost()) {
            $variable = $this->getRequest()->getPost('var',0);
            //内容
            $data = array();
            $data['variable'] = $variable;
            $data['value'] = $this->getRequest()->getPost('value', 0);
            $data['description'] = $this->getRequest()->getPost('description', 0);
            if ($this->db_config->update_config($data,['variable'=>$variable]) !== false) {
                $this->redirect('/admin/config/index');
            } else {
                $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/admin/document/index';
                $this->redirect($referer);
            }
        } else{
            $variable = $this->getRequest()->getQuery('variable');
            $data = $this->db_config->getOne('*',['variable'=>$variable]);
            $this->getView()->assign('data',$data);
            $this->getView()->assign('var',$variable);
        }
    }

    /**
     * 清除memcache缓存
     */
    public function flushAction(){
        if($this->db_config->flush_memcache()){
            Alert::success("操作成功");
            Yaf_Controller_Abstract::redirect("/admin/index/index");
            exit;
        }
    }

    /**
     * 获取cms版本信息
     */
    public function checkversionAction(){
	exit;
        $current_version = $this->db_config->get_config();
        $this->getView()->assign("data",$data);
    }

    public function settingAction(){
        if($this->getRequest()->getPost('dosubmit',0)){
            $data = $this->getRequest()->getPost('config');
            if($data){
                foreach($data as $key=>$value){
                    $this->db_config->update_option(['conf_value'=>$value],['conf_name'=>$key]);
                }
                Alert::success("操作成功");
                Yaf_Controller_Abstract::redirect($this->referer);
                exit;
            }else{
                Yaf_Controller_Abstract::redirect($this->referer);
                exit;
            }
            
        }else{
            $data = $this->db_config->get_config();
            $this->getView()->assign("data",$data);
        }
    }

    public function articlesettingAction(){
	exit;
       if($this->getRequest()->getPost('dosubmit',0)){
            $data = $this->getRequest()->getPost('config');
            if($data){
                foreach($data as $key=>$value){
                    $this->db_config->update_option(['conf_value'=>$value],['conf_name'=>$key]);
                }

                Alert::success("操作成功");
                Yaf_Controller_Abstract::redirect($this->referer);
                exit;
            }
        }else{
            $data = $this->db_config->get_config();
            $this->getView()->assign("data",$data);
        } 
    }
}
