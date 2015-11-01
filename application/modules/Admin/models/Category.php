<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

/**
栏目频道类
@author lucas lucasphper@gmail.com
@package admin
@access public
@version 1.0 2014/11/21
 */
include_once(PATH_APP . 'modules/Admin/models/Admin.php');

class CategoryModel extends AdminModel {

    function __construct() {
        parent::__construct();
    }

    /**
     * 获取栏目列表
     * array $cond where条件 根据id、栏目、标题、
     * int $start 分页起始页
     * int $offset 分页结束页
     * $order 排序 默认根据listorder
     * return array
     * @author cms
     */
    public function categoryList($cond, $start = 0, $offset = 999, $order = 'orderid', $select = ['catid', 'catname', 'catpath']) {
        $ret = [];
        if (isset($cond['catid'])) {
            $where['AND']['catid'] = $cond['catid'];
        }

        if (isset($cond['parentid'])) {
            $where['AND']['parentid'] = $cond['parentid'];
        }

        $where['LIMIT'] = [$start, $offset];
        $where['ORDER'] = $order;

        $data = $this->db->select('category', $select, $where);

        foreach ($data as $v) {
            $ret[$v['catid']] = $v;
        }

        return $ret;
    }


    /**
     *无限极分类实现
     *
     *@program int $pid 父类id
     *@program array $result 存放结果集
     *@program int $leve 遍历深度
     *@return array
     */
    public function catelist($pid=0, &$result=[], $leve=0){
        $where['parentid'] = $pid;
        $res = $this->db->select('category', '*', $where);

        //深度加一
        $leve++;
       foreach($res as $row){
            $row['catname'] = '|' . str_repeat($leve === 1 ? '' : '-',$leve*3) . $row['catname'];
            $result[] = $row;
            $this->catelist( $row['catid'], $result, $leve);
        }

        return $result;
    }


    /**
     *生成无限极分类结果
     *
     *@program int $pid 父类id
     *@program int $sid 被选择id
     *@return string
     */
    public function showlist($pid = 0, $sid = 0){

        $str = '<select name="">';
        $str .='<option value="0" selected>--请选择--</option>';
        foreach($this->catelist($pid) as $key){
            $selected = '';
            if($sid == $key['catid']){
                $selected = 'selected';
            }
            $str .= '<option ' . $selected . ' value="' .
                $key['catid'] . '">' . $key['catname'] . '</option>';
        }
        $str .="</select>";

        return $str;
    }


    /**
    获取文章栏目
    @param int $offset 偏移量
    @param int $per_page 每页显示条数
    @return array
     */
    function get_category_list($offset, $per_page) {
        $where['ORDER'] = 'orderid DESC';
        $where['LIMIT'] = [$offset, $per_page];
        return $this->db->select("category", '*', $where);
    }

    /**
      获取文章数量
      @param void
      @return int
     */
    function get_category_count() {
        return $this->db->count('category');
    }

    /**
      添加栏目
      @param array
      @return int
     */
    function add_category($category_detail = array(), $by = 'en_key') {
        $memkey = MEMPREFIX . 'category';
        $catid = $this->db->insert('category', $category_detail);
        $this->memcache->delete($memkey);
        $this->memcache->delete($memkey . $by);
        $this->memcache->delete($memkey . $by . '99');
        return $catid;
    }

    /**
      删除栏目
      @param int
      @return bool
     */
    function delete_category($category_id, $by = 'en_key') {
        $memkey = MEMPREFIX . 'category';
        $catid = $this->db->delete('category', array('catid' => $category_id));
        $this->memcache->delete($memkey);
        $this->memcache->delete($memkey . $by);
        $this->memcache->delete($memkey . $by . '99');
        return $catid;
    }

    /**
      栏目详情
      @param int $category_id 栏目id
      @return array
     */
    function get_category_detail($category_id) {
        return $this->db->select('category', '*', ['catid' => $category_id]);
    }

    /**
      更新栏目
      @param array
      @return bool
     */
    function update_category($category_detail, $by = 'en_key') {
        $memkey = MEMPREFIX . 'category';
        $category_id = (int) $category_detail['catid'];
        $detail = $this->db->update('category', $category_detail, ['catid' => $category_id]);
        $this->memcache->delete($memkey);
        $this->memcache->delete($memkey . $by);
        $this->memcache->delete($memkey . $by . '99');
        return $detail;
    }

    /**
     * 筛选栏目
     * @param array $columns 显示字段
     * @param array $where 筛选条件
     * @return array
     */
    public function filter_category($columns, $where) {
        return $this->db->select('category', $columns, $where);
    }


    /**
     * 获取栏目路径及父类id
     * @return mixed
     */
    public function get_catpath_list(){
        $memkey = MEMPREFIX . 'category_list';

        $catpath = $this->memcache->get($memkey);

        if($catpath == false){
            $catpath = $this->db->select('category', ['catid', 'catpath', 'parentid']);
            $this->memcache->set($memkey, $catpath, 36400);
        }

        return $catpath;
    }


    public function get_catpath($catid){
        $catpth = $this->db->select('category', ['catpath', 'parentid'], ['catid'=>$catid]);
        $path = $this->db->select('category','catpath',['catid'=>$catpth[0]['parentid']]);
        $path = $path[0] . '/' . $catpth[0]['catpath'] ;
        return $path;
    }


}
