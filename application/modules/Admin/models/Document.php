<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

/**
  文档类
  @author lucas lucasphper@gmail.com
  @package admin
  @access public
  @version 1.0 2014/11/27
 */
include_once(PATH_APP . 'modules/Admin/models/Admin.php');

class DocumentModel extends AdminModel {

    function __construct() {
        parent::__construct();
    }

    /**
     * 获取文章列表
     * @param array $cond where条件 根据id、栏目、标题、
     * @param int $start 分页起始页
     * @param int $offset 分页结束页
     * @param String $order 排序 默认根据listorder,然后根据inputtime DESC
     * @param array $select 
     * @return array
     * @author yangguofeng
     */
    public function newsList($cond, $start = 0, $offset = 20, $order = 'inputtime DESC', $select = ['id', 'catid', 'title', 'keywords', 'status', 'inputtime', 'updatetime', 'attribute']) {

        $memkey = MEMPREFIX . 'admin:list';

        $where = [];

        if (isset($cond['id'])) {
            $where['AND']['id'] = $cond['id'];
            $memkey .= ' .id' . $cond['id'];
        }

        if (isset($cond['catid'])) {
            $where['AND']['catid'] = $cond['catid'];
            $memkey .= '.catid' . $cond['catid'];
        }

        if (isset($cond['status'])) {
            $where['AND']['status'] = $cond['status'];
            $memkey .= '.status' . $cond['status'];
        }

        if (isset($cond['title'])) {
            $where['LIKE']['title%'] = $cond['title'];
            $memkey .= '.like' . $cond['title'];
        }

        $where['LIMIT'] = [$start, $offset];

        $where['ORDER'] = $order;

        $memkey .= '.limit' . ($start + $offset) . $order;

        $data = $this->memcache->get(md5($memkey));

        if (false == $data) {

            $data = $this->db->select('news', $select, $where);
            if(false != $data){
                $this->memcache->set(md5($memkey), $data, MEMCACHE_COMPRESSED, 10);
            }
        }

        return $data;
    }

    /**
     * 获取文章条数
     * @author yangguofeng
     */
    public function count($cond) {
        $where = [];
        if (isset($cond['catid'])) {
            $where['AND']['catid'] = $cond['catid'];
        }

        if (isset($cond['id'])) {
            $where['AND']['id'] = $cond['id'];
        }

        if (isset($cond['status'])) {
            $where['AND']['status'] = $cond['status'];
        }

        if (isset($cond['title'])) {
            $where['LIKE']['title'] = $cond['title'];
        }

        if (isset($cond['inputtime[>]'])) {
            $where['AND']['inputtime[>]'] = $cond['inputtime[>]'];
        }

        if (isset($cond['inputtime[<]'])) {
            $where['AND']['inputtime[<]'] = $cond['inputtime[<]'];
        }

        //$where['AND']['inputtime[<]'] = $time;

        return $this->db->count('news', $where);
    }

    /**
     * 根据id一条新闻
     * @param type $cond
     * @param type $select
     * @return type
     * @author yangguofeng
     */
    public function getNewsById($id) {
        return $this->db->select('news', [
                    '[>]news_data' => 'id'
                        ], [
                    'news.id',
                    'news.title',
                    'news.catid',
                    'news.keywords',
                    'news.description',
                    'news.attribute',
                    'news_data.content',
                        ], [
                    'news.id' => $id
        ]);
    }

    /**
     * 添加文章
     * @param type $data
     * @param type $table
     * @return type
     * @author yangguofeng
     */
    public function addNews($data) {
        $this->db->query('BEGIN');
        try {
            $id = $this->db->insert('news', [
                'title' => $data['title'],
                'catid' => $data['catid'],
                'listorder' => isset($data['listorder']) ? $data['listorder'] : 0,
                'keywords' => isset($data['keywords']) ? $data['keywords'] : '',
                'url' => isset($data['url']) ? $data['url'] : '',
                'uid' => isset($data['uid']) ? $data['uid'] : 0,
                'attribute' => isset($data['attribute']) ? $data['attribute'] : 0,
                'description' => isset($data['description']) ? $data['description'] : '',
                'inputtime' => isset($data['inputtime']) ? $data['inputtime'] : $_SERVER['REQUEST_TIME'],
                'updatetime' => isset($data['updatetime']) ? $data['updatetime'] : (isset($data['inputtime']) ? $data['inputtime'] : $_SERVER['REQUEST_TIME']),
                'status' => 99,
                    ]
            );
            if ($id) {
                $this->db->insert('news_data', [
                    'id' => $id,
                    'content' => $data['content'],
                    'defaultpic' => ''
                        ]
                );
            } else {
                throw new Exception;
            }

            $this->db->query('COMMIT');
            return TRUE;
        } catch (Exception $exc) {
            $this->db->query('ROLLBACK');
        }
        return FALSE;
    }

    /**
     * 批量删除文章
     * @param type $where
     * @return boolean
     * @throws Exception
     * @author yangguofeng
     */
    public function batchDeleteNews($where) {
        $this->db->query('BEGIN');
        try {
            if ($this->delete('news', $where)) {
                if ($this->delete('news_data', $where)) {
                    
                } else {
                    throw new Exception;
                }
            } else {
                throw new Exception;
            }

            $this->db->query('COMMIT');
            return TRUE;
        } catch (Exception $exc) {
            $this->db->query('ROLLBACK');
        }
        return FALSE;
    }

    /**
     * 获取文档数
     * @param array $condition 条件参数
     * @param string $tableName 表名
     * @return int
     */
    public function get_document_count($condition, $tableName = 'news') {
        $where = array();
        if (isset($condition['catid'])) {
            $where['AND']['catid'] = $condition['catid'];
        }

        if (isset($condition['status'])) {
            $where['AND']['status'] = $condition['status'];
        }


        return $this->db->count($tableName, $where);
    }

    /**
     * 获取文章列表
     * @param int $first 起始位置
     * @param int $limit 条数
     * @param array $condition 条件参数
     * @param string $tableName 表名
     * @return array
     */
    public function get_document_list($first, $limit, $condition, $tableName = 'news') {
        $where = array();
        if (isset($condition['catid'])) {
            $where['AND']['catid'] = $condition['catid'];
        }

        if (isset($condition['status'])) {
            $where['AND']['status'] = $condition['status'];
        }


        $where['ORDER'] = 'id DESC';
        $where['LIMIT'] = [$first, $limit];
        return $this->db->select($tableName, ['id', 'catid', 'uid', 'status', 'updatetime', 'title'], $where);
    }

    /**
     * 删除文档
     * @param int $document_id 文档id
     * @param string $tableName 表名
     * @return  bool $data
     */
    public function delete_document($document_id, $tableName = 'news',$start = 0, $offset = 20, $order = 'inputtime DESC') {
        $memkey = MEMPREFIX . 'admin:list.limit' . ($start + $offset) . $order;
        $data = $this->db->delete($tableName, array('id' => $document_id));

        //清memcache缓存
        $this->memcache->delete(MEMPREFIX . 'article:hits' . $document_id);
        $this->memcache->delete(MEMPREFIX . 'category');
        $this->memcache->delete(md5($memkey));
        $this->memcache->delete(MEMPREFIX . 'article:detail' . $document_id);

        return $data;
    }

    /**
     * 显示隐藏文档
     * @param array $document_detail 文档详情
     * @param string $tableName 表名
     * @return bool
     */
    public function document_display($document_detail, $tableName = 'news') {

        return $this->db->update($tableName, $document_detail, array('id' => $document_detail['id']));
    }

    /**
     * 文档详情
     * @param int $document_id 文档id
     * @return array
     */
    public function get_document_detail($document_id, $column = "*", $tableName = 'news') {
        if ($tableName == 'news') {
            $basic = $this->db->get($tableName, array('id', 'catid', 'uid', 'status', 'updatetime', 'url', 'title', 'keywords', 'description', 'listorder'), ['id' => $document_id]);
            $detail = $this->db->get('news_data', ['id', 'content'], ['id' => $document_id]);
            if ($detail) {
                return array_merge($basic, $detail);
            } else {
                return $basic;
            }
        } else {
            return $this->db->get($tableName, $column, ['id' => $document_id]);
        }
    }

    /**
     * 添加文档
     * @param array $document_detail 文档详情
     * @param string $tableName 表名
     * @return bool
     */
    public function document_add($data, $tableName = 'news') {
        return $this->db->insert($tableName, $data);
    }

    /**
     * 更新文档
     * @param array $document_detail 文档详情
     * @param string $tableName 表名
     * @return $data bool
     */
    public function document_update($document_id, $data, $tableName = 'news') {

        $data =  $this->db->update($tableName, $data, array('id' => $document_id));

        //清memcache缓存
        $this->memcache->delete(MEMPREFIX . 'article:hits' . $document_id);
        $this->memcache->delete(MEMPREFIX . 'category');
        $this->memcache->delete(md5(MEMPREFIX . 'admin:list'));
        $this->memcache->delete(MEMPREFIX . 'article:detail' . $document_id);

        return $data;
    }

    /**
     * @param int $document_id 文档id
     * @param array $data 要修改的数据
     */
    public function document_trash($document_id = 5, $data) {

        $data = $this->db->update('news', $data, ['id' => $document_id]);
        //清memcache缓存
        $this->memcache->delete(MEMPREFIX . 'article:hits' . $document_id);
        $this->memcache->delete(MEMPREFIX . 'category');
        $this->memcache->delete(md5(MEMPREFIX . 'admin:list'));
        $this->memcache->delete(MEMPREFIX . 'article:detail' . $document_id);

        return $data;
    }

    /**
     * 批量修改文章
     * @param type $where
     * @return boolean
     * @throws Exception
     * @author yangguofeng
     */
    public function batch_trash($where) {
        $this->db->query('BEGIN');
        try {
            if ($this->document_trash($where['id'], ['status' => 5, 'updatetime' => $_SERVER['time']])) {
                
            } else {
                throw new Exception;
            }

            $this->db->query('COMMIT');
            return TRUE;
        } catch (Exception $exc) {
            $this->db->query('ROLLBACK');
        }
        return FALSE;
    }



}
