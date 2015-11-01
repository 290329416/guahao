<?php

/**
 * 文章数据库操作类
 *
 * User: zhangxudong@lianchuangbrothers.com
 * Date: 14-11-14
 * Time: 11:12
 */
class TestModel extends BaseModel {

    /**
     * 初始化
     */
    function __construct() {
        parent::__construct();
        //导航
        $this->category = $this->category();
    }

    function get_keywords($where){
        //$order = 'inputtime ASC'
    	return $this->db->select('news',['id','catid','keywords','title','description','inputtime'],$where);
    }

    function insert_keywords_data($data){
    	return $this->db->insert('keywords_data',$data);
    }

    function get_catpath($where){
        return $this->db->get('category','catpath',$where);
    }
}