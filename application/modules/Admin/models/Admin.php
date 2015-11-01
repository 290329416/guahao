<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

/**
  公共模型类
  @author lucas lucasphper@gmail.com
  @access public
  @package core
  @version 1.0 2014/11/19
 */
class AdminModel {

    //配置信息
    protected $config;
    //db对象
    protected $db;
    //memcache信息
    protected $memcache;

    function __construct() {
        //初始化属性
        $this->config = Yaf_Registry::get('config');
        $this->db = Yaf_Registry::get('db');
        $this->memcache = Yaf_Registry::get('memcache');
        
        //cookie初始化
        $this->cookie = array(
            'cookie_pre' => Yaf_Registry::get('config')->get('cookie')->pre,
            'cookie_path' => Yaf_Registry::get('config')->get('cookie')->path,
            'cookie_domain' => Yaf_Registry::get('config')->get('cookie')->domain
        );
    }

    /**
     * 查询多条
     * @param type $table
     * @param type $join
     * @param type $columns
     * @param type $where
     * @author yangguofeng
     */
    public function select($table, $join, $columns = null, $where = null) {
        return $this->db->select($table, $join, $columns = null, $where = null);
    }

    /**
     * 查询一条
     * @param type $table
     * @param type $columns
     * @param type $where
     * @return type
     * @author yangguofeng
     */
    public function get($table, $columns, $where = null) {
        return $this->db->get($table, $columns, $where);
    }

    /**
     * 判断是否存在
     * @param type $table
     * @param type $join
     * @param type $where
     * @author yangguofeng
     */
    public function has($table, $join, $where = null) {
        return $this->db->has($table, $join, $where = null);
    }

    /**
     * 删除
     * @param type $table
     * @param type $condition
     * @author yangguofeng
     */
    public function delete($table, $condition = '') {
        return $this->db->delete($table, $condition);
    }

    /**
     * 更新
     * @param type $data
     * @param type $table
     * @param type $condition
     * @return type
     * @author yangguofeng
     */
    public function update($table, $data, $condition = '') {
        return $this->db->update($table, $data, $condition);
    }

    /**
     * 添加
     * @param $table
     * @param $datas
     * @return mixed
     */
    public function insert($table, $datas) {
        return $this->db->insert($table, $datas);
    }

    /**
     * 统计数量
     * @param $table
     * @param $where
     * @return mixed
     */
    public function count($table, $join = null, $column = null, $where = null) {
        return $this->db->count($table, $join, $column, $where);
    }

    public function get_last_query() {
        return $this->db->last_query();
    }

}
