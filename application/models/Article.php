<?php

/**
 * 文章数据库操作类
 *
 * User: zhangxudong@lianchuangbrothers.com
 * Date: 14-11-14
 * Time: 11:12
 */
class ArticleModel extends BaseModel
{

    /**
     * 初始化
     */
    function __construct()
    {
        parent::__construct();
        //导航
        // $this->category = $this->category();
    }

    /**
     * 获取文章详情
     * @param int $id
     * @return array $data;
     */
    public function detail($id)
    {
        // $memkey = MEMPREFIX . 'article:detail' . $id;
        $data = false;
        if (is_numeric($id)) {
            //$data = $this->memcache->get($memkey);

            // $data = false;
            if ($data == false) {
                $detail = $this->db->get('news', [
                        'id',
                        'catid',
                        'title',
                        'keywords',
                        'description',
                        'listorder',
                        'uid',
                        'username',
                        'url',
                        'islink',
                        'inputtime',
                        'updatetime',
                    ], [
                        'AND' => [
                            'id' => $id,
                            'status' => 99
                        ],
                    ]
                );
                // $data != false;
                if ($detail <> false) {
                    $data = $this->db->get('news_data', [
                            'content',
                            'fromweb',
                        ], [
                            'id' => $id
                        ]
                    );
                    $data == false && $data = [];
                    $data = array_merge($detail, $data);
                    $data['description'] == false && $data['description'] = utf8::substr(strip_tags($data['content']), 0, 78) . '...';
                }

                // $data <> false && $this->memcache->set($memkey, $data, MEMCACHE_COMPRESSED, 3600 * 24);
            }
        }
        return $data;
    }

    /*
     * 获取文章列表
     * array $cond where条件 attribute=1 推荐 attribute=2 精华
     * int $start 分页起始页
     * int $offset 分页结束页
     * int $memtime 缓存时间
     * $order 排序
     * return array
     */

    public function articleList($cond, $start = 0, $offset = 10, $order = 'inputtime DESC', $memtime = 60, $columns = ['id', 'catid', 'title', 'keywords', 'description', 'inputtime'])
    {
        // $memkey = MEMPREFIX . 'article:list';


        if ($cond['inputtime[>]']) {
            $where['AND']['inputtime[>]'] = $cond['inputtime[>]'];
            // $memkey .= 'inputtime[>]' . $cond['inputtime[>]'];
        }
        if ($cond['id[>]']) {
            $where['AND']['id[>]'] = $cond['id[>]'];
            // $memkey .= 'id[>]' . $cond['id[>]'];
        }
        if ($cond['id[<]']) {
            $where['AND']['id[<]'] = $cond['id[<]'];
            // $memkey .= 'id[<]' . $cond['id[<]'];
        }

        if (isset($cond['LIKE'])) {
            $where['LIKE'] = $cond['LIKE'];
            // $memkey .= '.like' . var_export($cond['LIKE'], TRUE);
        }

        if (isset($cond['catid'])) {
            $where['AND']['catid'] = $cond['catid'];
            // $memkey .= '.catid' . $cond['catid'];
        }
        if (isset($cond['attribute'])) {
            $where['AND']['attribute'] = $cond['attribute'];
            // if (is_array($cond['attribute']))
                // $memkey .= ' .attribute' . implode('', $cond['attribute']);
            // else
                // $memkey .= ' .attribute' . $cond['attribute'];
        }
        if (isset($cond['id'])) {
            $where['AND']['id'] = $cond['id'];
            // if (is_array($cond['id']))
                // $memkey .= ' .id' . implode('', $cond['id']);
            // else
                // $memkey .= ' .id' . $cond['id'];
        }

        if (isset($cond['startTime'])) { //tools
            $where['AND']['inputtime[<>]'] = [$cond['startTime'], $cond['endTime']];
            // $memkey .= '.startTime' . $cond['startTime'];
        } else {
            $time = time();
            $time = $time - $time % 100;
            $where['AND']['inputtime[<]'] = $time;
        }

        $where['AND']['status'] = 99;
        if ($start || $offset) {
            $where['LIMIT'] = [$start, $offset];
            // $memkey .= '.limit' . ($start + $offset) . $order;
        }
        if ($order) {
            $where['ORDER'] = $order;
            // $memkey .= $order;
        }
        // $data = $this->memcache->get(md5($memkey));
        $data = false;
        if ($data == false) {
            $data = $this->db->select('news', $columns, $where);
            // $data <> false && $this->memcache->set(md5($memkey), $data, MEMCACHE_COMPRESSED, $memtime);
        }
        return $data;
    }

    //获取文章条数
    public function getTitleCount($cond)
    {
        $memkey = MEMPREFIX . 'index:gettitlecount';

        if (isset($cond['catid'])) {
            $where['AND'] = ['catid' => $cond['catid']];
            $memkey .= '.catid' . $cond['catid'];
        }

        if (isset($cond['LIKE'])) {
            $where['LIKE'] = $cond['LIKE'];
            $memkey .= '.like' . var_export($cond['LIKE'], TRUE);
        }
        if (isset($cond['inputtime[>]'])) {
            $where['AND']['inputtime[>]'] = $cond['inputtime[>]'];
            $memkey .= '.inputtime' . $cond['inputtime[>]'];
        }
        if (isset($cond['startTime'])) {
            $where['AND']['inputtime[<>]'] = [$cond['startTime'], $cond['endTime']];
            $memkey .= '.startTime' . $cond['startTime'];
        } else {
            $time = time();
            $time = $time - $time % 100;
            //排除当前以后的数据
            $where['AND']['inputtime[<]'] = $time;
        }
        $where['AND']['status'] = 99;

        //$data = $this->memcache->get($memkey);
        if ($data == false) {
            $data = $this->db->count('news', $where);
            //$data <> false && $this->memcache->set($memkey, $data, MEMCACHE_COMPRESSED, 600);
        }
        return $data;
    }

    /*
     * 根据文章id获得该文章的上一篇下一篇
     * int $id
     * return array
     */
    function preNext($inputtime = 0, $cond)
    {
        $data = false;
        if (is_numeric($inputtime)) {
            $memkey = MEMPREFIX . 'article:prenext' . $cond['id'];

            if (isset($cond['catid']) && $cond['catid']) {
                $where['AND'] = ['catid' => $cond['catid']];
                $memkey .= '.catid' . $cond['catid'];
            }

            $where['AND']['status'] = 99;

            //$data = $this->memcache->get($memkey);
            if ($data == false) {
                //上一篇
                $where['AND']['id[<]'] = $cond['id'];
                $where['ORDER'] = 'id DESC';
                $previous = $this->db->get('news', ['id', 'catid', 'title'], $where);
//                echo $cond['id'].'</br>';
//                echo $this->db->last_query().'</br>';
                if ($previous == false)
                    $data['previous'] = '没有了';
                else
                    $data['previous'] = '上一篇：<a href="/' . SITEDIR . '/' . $this->category[$previous['catid']]['catpath'] . '/' . $previous['id'] . '.html" title="' . $previous['title'] . '">' . $previous['title'] . '</a>';

                //下一篇
                unset($where['AND']['id[<]']);
                $where['ORDER'] = 'id ASC';
                $where['AND']['id[>]'] = $cond['id'];
                $next = $this->db->get('news', ['id', 'catid', 'title'], $where);
//                echo $this->db->last_query().'</br>';
                if ($next == false)
                    $data['next'] = '没有了';
                else
                    $data['next'] = '下一篇：<a href="/' . SITEDIR . '/' . $this->category[$next['catid']]['catpath'] . '/' . $next['id'] . '.html" title="' . $next['title'] . '">' . $next['title'] . '</a>';
                //$this->memcache->set($memkey, $data, MEMCACHE_COMPRESSED, 3601 * 2);
            }
        }

        return $data;
    }

    /*
     * 热点阅读
     * cond条件，limit偏移量
     */

    function hothit($cond, $limit)
    {
        $memkey = MEMPREFIX . 'article:hothit';
        if (isset($cond['catid'])) {
            $where['AND']['catid'] = $cond['catid'];
            $memkey .= '.catid' . $cond['catid'];
        }
        $where['LIMIT'] = $limit;
        $where['ORDER'] = 'views DESC';
        $memkey .= '.limit' . $limit;
        //$aid = $this->memcache->get('$memkey');
//        $aid = false;
        if ($aid == false) {
            $aid = $this->db->select('hits', ['catid', 'aid', 'views'], $where);
            //$this->memcache->set($memkey, $aid, MEMCACHE_COMPRESSED, 3600);
            $data = [];
            $val = [];
            if (!empty($aid)) {
                foreach ($aid as $ids) {
                    $id[] = $ids['aid'];
                    $val[$ids['aid']] = $ids['views'];
                }
                $data = $this->articleList(['id' => $id]);
                foreach ($data as &$d) {
                    $d['views'] = $val[$d['id']];
                }
            }
            return $data;
        }
    }

    /*
     * 为data数组添加浏览量字段
     */
    function getView($data) {
        foreach ($data as &$val) {
            $val['views'] = rand(30,400);
        }
        return $data;
    }

    /*
     * 为data数组添加图片字段
     */

    function getPic($data)
    {
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

    function getCatname($data)
    {
        foreach ($data as &$val) {
            //$num = $this->memcache->get(MEMPREFIX . 'article:catpath' . $val['catid']);
            //$num = false;
            if ($num == false) {
                $nums = $this->db->get('category', ['catpath'], ['catid' => $val['catid']]);
                $num = $nums == false ? 0 : $nums['catpath'];
                //$this->memcache->set(MEMPREFIX . 'article:catpath' . $val['catid'], $num, 0, 3600);
            }
            $val['catpath'] = $num;
        }
        return $data;
    }

    function updateViews($id, $cid)
    {
        if ($this->db->get('hits', ['views'], ['aid' => $id])) {
            return $this->db->update("hits", ["views[+]" => 1], ["aid" => $id]);
        } else {
            return $this->db->insert("hits", ["aid" => $id, "views" => 1, "catid" => $cid]);
        }
    }

    //获取关键词相关列表
    function getKeyList($key, $start = 0, $offset = 20, $order = 'inputtime DESC')
    {
        $where['AND']['keyword_path'] = $key;
        $where['LIMIT'] = [$start, $offset];
        $where['ORDER'] = $order;
        if ($key == false) {
            $data = $this->db->select('keywords_data', ['id', 'catid', 'title', 'keywords', 'description', 'inputtime'], $where
            );
        }
        return $data;
    }

    //获得文章关键词中的数据
    function get_keywords() {

        return $this->select('keywords', ['keywords']);
    }

    //替换文章关键词敏感字
    function replace_keywords($words) {
        $replacewords = $this->get('keywords', ['replace_words'], ['keywords' => $words]);
        return $replacewords['replace_words'];
    }

    //获得内链关键词表中的数据
    function get_key_datas() {
        //$keyDatas = $this->memcache->get(MEMPREFIX . 'key:keyDatas');
        if ($keyDatas == FALSE) {

            $keyDatas = $this->select('content_keywords', ['keywords']);
            //$this->memcache->set(MEMPREFIX . 'key:keyDatas', $keyDatas, 0, 600);
        }
        return $keyDatas;
    }

    //替换关键词的链接
    function replace_key_url($key) {
        return $this->get('content_keywords', ['href_url'], ['keywords' => $key]);
    }

    public function tag($data, $keywords)
    {
        $memkey = MEMPREFIX . 'tag' . $keywords;
        //$tag = $this->memcache->get($memkey);
        if ($tag) {
            return $tag;
        }
        foreach ($data as $v) {
            if (strpos($v['keywords'], ' ')) {
                $_keywords = explode(' ', $v['keywords']);
                foreach ($_keywords as $value) {
                    if (strcmp(Pinyin::get($value), $keywords) === 0) {
                        $tag = $value;
                        //$this->memcache->set($memkey, $tag, MEMCACHE_COMPRESSED, 108000);
                        return $tag;
                    }
                }
            } else if (strcmp(Pinyin::get($v['keywords']), $keywords) === 0) {
                $tag = trim($v['keywords']);
                //$this->memcache->set($memkey, $tag, MEMCACHE_COMPRESSED, 108000);
                return $tag;
            }
        }
    }

    /*取得当前栏目下文章的最新时间点
     *int $cid 分类id
     *return int 时间戳
     */
    function get_new_time($cid)
    {
        //$time用来存储最新时间
        $time = [];
        $cat['AND']['catid'] = $cid;
        $cat['AND']['attribute'] = [1, 2, 3];
        $cat['AND']['status'] = 99;
        $cat['LIMIT'] = 1;
        //如果没有cid参数默认取全站中最新的文章发布时间
        $allweb['catid[>]'] = 0;
        $allweb['AND']['attribute'] = [1, 2, 3];
        $allweb['AND']['status'] = 99;
        $allweb['LIMIT'] = 1;

        $time['cattime'] = $this->db->max('news', 'inputtime', $cat);
        $time['allweb'] = $this->db->max('news', 'inputtime', $allweb);

        return $time;
    }

    /*
     * 文章列表页分页
     * array $cond where条件 attribute=1 推荐 attribute=2 精华
     * int $start 分页起始页
     * int $offset 分页结束页
     * int $memtime 缓存时间
     * $order 排序
     * return array
     */
    public function pageList($cond, $start = 0, $offset = 10, $order = 'inputtime DESC', $memtime = 600, $columns = ['id', 'catid', 'title', 'keywords', 'description', 'inputtime']) {
        // $memkey = MEMPREFIX . 'article:list';

        $catid_sql = ' status=99 ';

        if (isset($cond['id'])) {
            $catid_sql .= ' id='.$cond['id'];
            if (is_array($cond['id']))
                $memkey .= ' .id' . implode('', $cond['id']);
            else
                $memkey .= ' .id' . $cond['id'];
        }


        if (isset($cond['catid'])) {
            $catid_sql .= ' AND catid='.$cond['catid'];
            $memkey .= '.catid' . $cond['catid'];
        }

        if (isset($cond['attribute'])) {
            if (is_array($cond['attribute'])){
                $catid_sql .= ' AND attribute IN ('.implode(',', $cond['attribute']) .')';
                $memkey .= ' .attribute' . implode('', $cond['attribute']);
            }else{
                $catid_sql .= ' AND attribute='.$cond['attribute'];
                $memkey .= ' .attribute' . $cond['attribute'];
            }
        }

        
        if (isset($cond['startTime'])) { //tools
            $catid_sql .= ' AND inputtime BETWEEN '.$cond['startTime'] . ' AND '.$cond['endTime'];
            $memkey .= '.startTime' . $cond['startTime'];
        }

        if (isset($cond['inputtime[>]'])) {
            $catid_sql .= ' AND inputtime > '.$cond['inputtime[>]'];
            $memkey.='inputtime[>]' . $cond['inputtime[>]'];
        }

        if ($order) {
            $catid_sql .= " ORDER BY ".$order;
            $memkey .= $order;
        }

        if ($start || $offset) {
            $catid_sql .= " LIMIT $start,$offset";
            $memkey .= '.limit' . ($start + $offset) . $order;
        }

        // $data = $this->memcache->get(md5($memkey));

        $sql ="SELECT t1.* FROM news t1,( SELECT id FROM news WHERE " . $catid_sql . " ) t2 WHERE t1.id = t2.id";

        if ($data == false) {
            $data = $this->query($sql);
            // $this->memcache->set(md5($memkey), $data, MEMCACHE_COMPRESSED, $memtime);
        }

        return $data;
    }

    //获取search页数据
    public function searchList($cond, $start = 0, $offset = 10, $memtime = 300) {
        
        $memkey =  'article:searchlist';

        if (isset($cond['LIKE'])) {
            $where['LIKE']['en_keywords'] = $cond['LIKE'];
            $memkey .= '.like' . $cond['LIKE']['en_keywords'];
        }
        if (isset($cond['inputtime[>]'])) {
            $where['AND']['inputtime[>]'] = $cond['inputtime[>]'];
            $memkey .= '.inputtime' . $cond['inputtime[>]'];
        }

        $where['LIMIT'] = [$start, $offset];
        $memkey .= '.limit' . ($start + $offset) . $order;

        $data = false;
        // $data = $this->memcache->get(md5($memkey));

        if ($data == false) {
            $data = $this->db->select('keywords_data',['id', 'catpath', 'title', 'keywords', 'inputtime','en_keywords'],$where);
            // $data <> false && $this->memcache->set(md5($memkey), $data,  $memtime);
        }
        return $data;
    }

    function get_title_count($cond){
        $memkey = MEMPREFIX . 'index:gettitlecount:like:';

        if (isset($cond['catid'])) {
            $where['AND'] = ['catid' => $cond['catid']];
            $memkey .= '.catid' . $cond['catid'];
        }

        if (isset($cond['LIKE'])) {
            $where['LIKE'] = $cond['LIKE'];
            $memkey .= '.like' . var_export($cond['LIKE'], TRUE);
        }
        
        if (isset($cond['inputtime[>]'])) {
            $where['AND']['inputtime[>]'] = $cond['inputtime[>]'];
            $memkey .= '.inputtime' . $cond['inputtime[>]'];
        }

        if (isset($cond['startTime'])) {
            $where['AND']['inputtime[<>]'] = [$cond['startTime'], $cond['endTime']];
            $memkey .= '.startTime' . $cond['startTime'];
        } else {
            $time = time();
            $time = $time - $time % 100;
            //排除当前以后的数据
            $where['AND']['inputtime[<]'] = $time;
        }
        
        //$data = $this->memcache->get($memkey);
        if ($data == false) {
            $data = $this->db->count('keywords_data', $where);
            //$data <> false && $this->memcache->set($memkey, $data, MEMCACHE_COMPRESSED, 600);
        }
        return $data;
    }
}

