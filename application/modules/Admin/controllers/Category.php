<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');
/**
  栏目类
  @author lucas lucasphper@gmail.com
  @access public
  @package base
  @version 1.0 2014/11/21
 */
Yaf_Loader::import(PATH_APP . 'modules/Admin/controllers/Admin.php');
Yaf_Loader::import(PATH_APP . 'modules/Admin/models/Category.php');
Yaf_Loader::import(PATH_APP . 'modules/Admin/models/Modeldata.php');

class CategoryController extends AdminController {

    /**
      初始化
      @param void
      @return void
     */
    function init() {
        parent::init();

        //db 实例化
        $this->category = new CategoryModel();

        $this->modeldata = new ModeldataModel();
    }

    /**
      默认页
      @param void
      @return void
     */
    function indexAction() {

        $data = [];
        $cond = [];
        $cond['status'] = 99;
        $url = '/admin/document/index?1=1';
        $page = $this->getRequest()->getQuery('page') ? intval($this->getRequest()->getQuery('page')) : 1;

        if ($this->getRequest()->getQuery('catsubmit')) {
            $url .= '&catsubmit=catsubmit';

            if ($catid = intval($this->getRequest()->getQuery('catid'))) {
                $cond['catid'] = $catid;
                $url .= '&catid=' . $cond['catid'];
            }
            if ($title = core::safe_str($this->getRequest()->getQuery('title'))) {
                $cond['title'] = $title;
                $url .= '&title=' . $cond['title'];
            }

            $data['data'] = $this->category->catelist($cond['catid']);
            $this->getView()->assign('cond', $cond);
        }


        //每页显示数
        $offset = 50;
        $start = ($page - 1) * $offset;
        $category_list = $this->category->catelist();

        $data['data'] ? $data['data'] : $data['data'] = $category_list;

        //总页数
        $total = $this->category->get_category_count();
        $totalpage = ceil($total / $offset);

        //超过一页显示分页
        if ($totalpage > 1) {
            $data['page'] = (new multi())->getSubContent('/admin/category/index?page=', '', $totalpage, $page, 9, ' target="_self" ', '', '', 'no');
        }
        $this->getView()->assign('data', $data);

        //格式化栏目列表
        //$category_list_format = $this->treeForCategory($category_list, 7);
        $this->getView()->assign('category_list', $category_list);
    }


    /**
     * 添加栏目
     * @param void
     * @return void
     */
    function dealAddAction() {
        //参数接收和处理
        $category_detail['catname'] = trim($this->getRequest()->getPost('cate_name_ch', 0));
        $category_detail['diytitle'] = trim($this->getRequest()->getPost('diytitle', 0));
        $category_detail['catpath']  = trim($this->getRequest()->getPost('cate_name_en', 0));
        $category_detail['orderid'] = intval($this->getRequest()->getPost('cate_order', 0));
        $category_detail['showtype'] = intval($this->getRequest()->getPost('cate_status', 0));
        $category_detail['keyword'] = trim($this->getRequest()->getPost('cate_keyword', 0));
        $category_detail['description'] = trim($this->getRequest()->getPost('cate_info', 0));
        $category_detail['parentid'] = intval($this->getRequest()->getPost('parent_category', 7));
        $category_detail['modelid'] = intval($this->getRequest()->getPost('model_data', 0));

        if (!($category_detail['catname'] && $category_detail['catpath'])) {
            Alert::danger('栏目名称和路径为必填项');
            $this->redirect("/admin/category/add/");
            exit;
        }


        //添加数据
        $category_id = $this->category->add_category($category_detail);
        if($category_id){
            Alert::success('添加成功！');
            Yaf_Controller_Abstract::redirect("/admin/category/index/");
            exit;
        }else{
            Alert::success('添加失败,清重新添加');
            Yaf_Controller_Abstract::redirect("/admin/category/add/");
            exit;
        }

    }


    /**
     * 添加栏目
     * @param void
     * @return void
     */
    function dealEditAction() {
        //参数接收和处理
        $category_detail['catid'] = trim($this->getRequest()->getPost('cid', 0));
        $category_detail['catname'] = trim($this->getRequest()->getPost('cate_name_ch', 0));
        $category_detail['diytitle'] = trim($this->getRequest()->getPost('diytitle', 0));
        $category_detail['catpath']  = trim($this->getRequest()->getPost('cate_name_en', 0));
        $category_detail['orderid'] = intval($this->getRequest()->getPost('cate_order', 0));
        $category_detail['showtype'] = intval($this->getRequest()->getPost('cate_status', 0));
        $category_detail['keyword'] = trim($this->getRequest()->getPost('cate_keyword', 0));
        $category_detail['description'] = trim($this->getRequest()->getPost('cate_info', 0));
        $category_detail['parentid'] = intval($this->getRequest()->getPost('parent_category', 7));
        $category_detail['modelid'] = intval($this->getRequest()->getPost('model_data', 0));

        if (!($category_detail['catname'] && $category_detail['catpath'])) {
            Yaf_Controller_Abstract::redirect("/admin/category/edit?cid={$category_detail['catid']}");
            Alert::danger('栏目名称和路径为必填项');
            exit;
        }


        ///更新数据
        $category_id = $this->category->update_category($category_detail);
        if ($category_id) {
            Alert::success('栏目修改成功');
            Yaf_Controller_Abstract::redirect("/admin/category/index/");
            exit;
        } else {
            Yaf_Controller_Abstract::redirect("/admin/category/edit?cid={$category_detail['catid']}");
            Alert::danger('栏目无修改或修改不成功，请再核对信息');
            exit;
        }

    }

    /**
     * 删除栏目
     * @param void
     * @return void
     */
    function deleteAction() {
        //接收参数
        $category_id = (int) $_GET['cid'];

        //暂不考虑管理员权限
        if ($category_id) {
            $result = $this->category->delete_category($category_id);
            if (!$result) {
                Alert::danger('删除失败');
                Yaf_Controller_Abstract::redirect("/admin/category/index/");
            } else {
                Alert::success('删除成功');
                Yaf_Controller_Abstract::redirect("/admin/category/index/");
            }
        } else {
            Yaf_Controller_Abstract::redirect("/admin/category/index/");
        }
        exit;
    }

    /**
    更新栏目
    @param void
    @return void
     */
    function editAction() {
        //栏目详情
        if (isset($_GET['cid']) && (int) $_GET['cid']) {
            $category_detail = $this->category->get_category_detail((int) $_GET['cid']);
            $this->getView()->assign('category_detail', $category_detail[0]);
        }

        //页码
        if (isset($_GET['pageid']) && (int) $_GET['pageid']) {
            $this->getView()->assign('pageid', $_GET['pageid']);
        }

        //模型数据
        if (isset($_GET['modelid']) && (int) $_GET['modelid']) {
            $this->getView()->assign('modelid', $_GET['modelid']);
        }

        //获取栏目列表
        $category_count = $this->category->get_category_count();
        $category_list = $this->category->get_category_list(0, $category_count);
        //$category_list_format = $this->treeForCategory($category_list);

        //格式化栏目列表
        $this->getView()->assign('category_list', $category_list);

        //获得模型数据的总数
        $result = $this->modeldata->get_model_count();
        $model_count = (int) $result[0]['total'];
        //获取模型数据列表
        $model_data = $this->modeldata->get_model_list(0, $model_count);

        //格式化模型数据列表
        $this->getView()->assign('model_data', $model_data);

    }

    /**
     * 添加栏目页
     * @param void
     * @return void
     */
    public function addAction() {

        //获取栏目列表
        $category_list = $this->category->catelist();
        //$category_list_format = $this->treeForCategory($category_list);

        //格式化栏目列表
        $this->getView()->assign('category_list', $category_list);

        //获得模型数据的总数
        $result = $this->modeldata->get_model_count();
        $model_count = (int) $result[0]['total'];
        //获取模型数据列表
        $model_data = $this->modeldata->get_model_list(0, $model_count);

        //格式化模型数据列表
        $this->getView()->assign('model_data', $model_data);
    }



}
