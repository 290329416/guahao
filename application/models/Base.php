<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

class BaseModel {

    /**
     * 所有配置信息.
     *
     * @var Yaf_Config_Ini
     */
    protected $config;

    /*
     * db
     */
    protected $db;
    /*
     * memcache
     */
    //protected $memcache;
    /*
     * redis
     */
    protected $redis;

    function __construct() {
        //配置文件
        $this->config = Yaf_Registry::get('config');
        //数据库
        $this->db = Yaf_Registry::get('db');
        //memcache
        //$this->memcache = Yaf_Registry::get('memcache');
        
        //redis
        $this->redis = Yaf_Registry::get('redis');
        
        //cookie初始化
        $this->cookie = ['cookie_pre' => Yaf_Registry::get("config")->get('cookie')->pre, 'cookie_path' => Yaf_Registry::get("config")->get('cookie')->path, 'cookie_domain' => Yaf_Registry::get("config")->get('cookie')->domain];
    }

    /**
     * 
     * 导航
     * return Array
     */
    function category($by = '') {
        $memkey = MEMPREFIX . 'category' . $by;
        //$data = $this->memcache->get($memkey);
//        $data = false;
        if ($data == false) {
            $data = [];
            $temp = $this->db->select('category', [
                'catid',
                'en',
                'pc_show_limit',
                'wap_show_limit',
                'catname',
                'catpath',
                'keyword',
                'description'
                    ], [
                'parentid' => 7,
                "ORDER" => "orderid DESC"
                    ]
            );


            if ($by == "en_key")
                foreach ($temp as $v)
                    $data[$v['catpath']] = $v;
            else
                foreach ($temp as $v)
                    $data[$v['catid']] = $v;

            //缓存一小时
            //$data <> false && $this->memcache->set($memkey, $data, MEMCACHE_COMPRESSED, 3603 * 24);
        }
        return $data;
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
     * @param type $table
     * @param type $join
     * @param type $column
     * @param type $where
     * @return type
     */
    public function count($table, $join = null, $column = null, $where = null) {
        return $this->db->count($table, $join, $column, $where);
    }

    /**
     * 查询多条
     * @param type $table
     * @param type $join
     * @param type $columns
     * @param type $where
     * @return type
     */
    public function select($table, $join, $columns = null, $where = null) {
        return $this->db->select($table, $join, $columns, $where);
    }
    
    public function getLastQuery() {
        return $this->db->last_query();
    }

    public function query($sql){
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * 获取option网站配置信息表（option缓存）
     */
    public function get_option_info(){
        $memkey = MEMPREFIX . 'option';

        $tmp = false;

        //$tmp = $this->memcache->get($memkey);

        if(!$tmp){
            $tmp = $this->db->select('option',['conf_name','conf_value'],['conf_id[>]'=>0]);

            $data = [];

            foreach($tmp as $v){
                $data[$v['conf_name']] = $v['conf_value'];
            }

            //$this->memcache->set($memkey,$data,MEMCACHE_COMPRESSED, 3600 * 24);

            return $data;
        }else{
            return $tmp;
        }
    }
    /**
     * 获取一级栏目导航
     * return Array
     */
    public function navlist(){
        $catlist = $this->db->select('category',['catid','catname','catpath'],['parentid' => 0,"ORDER" => "catid DESC"]);
        return $catlist;
    }
}
