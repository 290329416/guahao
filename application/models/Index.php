<?php

/**
 * 首页数据库操作类
 *
 * User: zhangxudong@lianchuangbrothers.com
 * Date: 14-11-14
 * Time: 11:12
 */
class IndexModel extends BaseModel {

    /**
     * 初始化
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 按文章点击量
     *
     * @param int $limit 影响行数
     * @return array
     */
    public function get_article_by_views($limit = 7) {
        //$result = $this->memcache->get(MEMPREFIX . 'index:news:hits:limit:' . $limit);
        $result = false;
        if ($result === false) {
            $hits = $this->db->select('hits', ['aid', 'views'], ['ORDER' => 'views DESC', 'LIMIT' => $limit]);
            $result = [];
            foreach ($hits as $v) {
                $result['aid'][] = $v['aid'];
                $result['hits'][$v['aid']] = $v['views'];
            }
            //缓存一小时
            //$this->memcache->set(MEMPREFIX . 'index:news:hits:limit:' . $limit, $result, MEMCACHE_COMPRESSED, 3600);
        }

        $news = $this->db->select('news', ['id', 'catid', 'title', 'description'], ['AND' => ['status' => 99, 'id' => $result['aid']]]);
        foreach ($news as &$v) {
            $v['views'] = $result['hits'][$v['id']];
        }
        ;
        return $news;
    }

    /*
     * 获取文章列表
     *
     * array $cond where条件 attribute=1 推荐 attribute=2 精华
     * int $memtime 缓存时间
     * $order 排序
     * @param array $field 查询字段
     * @param int $mobile 是否手机
     * return array
     */

    public function articleList($cond, $order = 'inputtime DESC', $page = 1, $limit = 6, $memtime = 60, $mobile = 1) {
        $offset = max($page - 1, 0) * $limit;
        if ($mobile == 1) {
            $memkey = MEMPREFIX . 'index : mobile';
            $field = ['id', 'catid', 'title'];
        } else {
            $memkey = MEMPREFIX . 'index';
            $field = ['id', 'catid', 'title', 'description','inputtime'];
        }
        if (isset($cond['catid'])) {
            $where['AND']['catid'] = $cond['catid'];
            $memkey .= '.catid' . $cond['catid'];
        }
        if (isset($cond['attribute'])) {
            $where['AND']['attribute'] = intval($cond['attribute']);
            $memkey .= '.attribute' . $cond['attribute'];
        }
        if (isset($cond['id'])) {
            $where['AND']['id'] = $cond['id'];
            if (is_array($cond['id']))
                $memkey .= ' .id' . implode('', $cond['id']);
            else
                $memkey .= ' .id' . $cond['id'];
        }
        $time = time();
        $time = $time - $time % 100;
        $where['AND']['inputtime[<]'] = $time;
        $where['AND']['status'] = 99;
        $where['LIMIT'] = [$offset, $limit];
        $where['ORDER'] = $order;
        $memkey .= '.limit' . ($offset + $limit) . $order;
        //$data = $this->memcache->get(md5($memkey));
        
        if ($data == false) {
            $data = $this->db->select('news', $field, $where
            );

            if ($data <> false) {
                if ($mobile == 1) {//手机获取浏览量
                    $data = $this->getView($data);
                }
                //$this->memcache->set(md5($memkey), $data, MEMCACHE_COMPRESSED, $memtime);
            }
        }
        return $data;
    }

    /**
     * 获取文章列表（包括点击数）
     *
     * array $cond where条件 attribute=1 推荐 attribute=2 精华
     * int $memtime 缓存时间
     * $order 排序
     * @param array $field 查询字段
     * @param int $mobile 是否手机
     * return array
     */
    public function articleListDetail($cond, $order = 'inputtime DESC', $page = 1, $limit = 6, $memtime = 60, $mobile = 1) {

        $offset = max($page - 1, 0) * $limit;
        // if ($mobile == 1) {
            // $memkey = MEMPREFIX . 'index : mobile';
            // $memkey .= '.joinhits';
        // } else {
            // $memkey = MEMPREFIX . 'index';
        // }
        if (isset($cond['catid'])) {
            $where['AND']['news.catid'] = $cond['catid'];
            $memkey .= '.catid' . $cond['catid'];
        }
        if (isset($cond['attribute'])) {
            $where['AND']['attribute'] = intval($cond['attribute']);
            $memkey .= '.attribute' . $cond['attribute'];
        }
        if (isset($cond['id'])) {
            $where['AND']['id'] = $cond['id'];
            if (is_array($cond['id']))
                $memkey .= ' .id' . implode('', $cond['id']);
            else
                $memkey .= ' .id' . $cond['id'];
        }
        $time = time();
        $time = $time - $time % 100;
        $where['AND']['inputtime[<]'] = $time;
        $where['AND']['status'] = 99;
        $where['LIMIT'] = [$offset, $limit];
        $where['ORDER'] = $order;
        $memkey .= '.limit' . ($offset + $limit) . $order;
        // $data = $this->memcache->get(md5($memkey));

        //$data = false
        if ($data == false) {
            // if ($mobile == 1) {//手机获取浏览量
                // $data = $this->db->select('news', ["[>]hits" => ["id" => "aid"]], ['id', 'news.catid', 'title', 'description', 'views','inputtime'], $where);
            // } else {
                $data = $this->db->select('news', ['id', 'catid', 'title','inputtime'], $where);
            // }
            // $this->memcache->set(md5($memkey), $data, MEMCACHE_COMPRESSED, $memtime);
        }
        return $data;
    }

    /*
     * 为data数组添加浏览量字段
     *
     */

    function getView($data) {
        foreach ($data as &$val) {
            //$num = $this->memcache->get(MEMPREFIX . 'article:hits' . $val['id']);
            //$num = false;
            if ($num == false) {
                $nums = $this->db->get('hits', ['views'], ['aid' => $val['id']]);
                $num = $nums == false ? 0 : $nums['views'];
                //$this->memcache->set(MEMPREFIX . 'article:hits' . $val['id'], $num, 0, 3600);
            }
            $val['views'] = $num;
        }
        return $data;
    }

    /*
     * 为data数组添加图片字段
     */

    function getPic($data) {
        foreach ($data as $val) {
            $arr[$val['id']] = $val;
            //$pic = $this->memcache->get(MEMPREFIX . 'article:pic' . $val['id']);
            if ($pic == false)
                $id[] = $val['id'];
            else
                $arr[$val['id']]['pic'] = $pic;
        }
        if (!empty($id)) {
            $data = $this->db->select('uploadfile', ['filepath', 'aid'], ['aid' => $id]);
            foreach ($data as $k => $v) {
                if (isset($arr[$v['aid']])) {
                    //$this->memcache->set(MEMPREFIX . 'article:pic' . $v['aid'], $v['pic'], MEMCACHE_COMPRESSED, 3600 * 24 * 30);
                    $arr[$v['aid']]['pic'] = $v['filepath'];
                }
            }
        }
        return $arr;
    }

    function getCatname($data) {
        foreach ($data as &$val) {
            //$num = $this->memcache->get(MEMPREFIX . 'article:catpath' . $val['catid']);
            $num = false;
            if ($num == false) {
                $nums = $this->db->get('category', ['catpath'], ['catid' => $val['catid']]);
                $num = $nums == false ? 0 : $nums['catpath'];
                //$this->memcache->set(MEMPREFIX . 'article:catpath' . $val['catid'], $num, 0, 3600);
            }
            $val['catpath'] = $num;
        }
        return $data;
    }

}
