<?php

/**
 * 文章控制类
 *
 * User: zhangxudong@lianchuangbrothers.com
 * Date: 14-11-14
 * Time: 10:02
 */
class ArticleController extends BaseController
{

    /**
     * 初始化
     */
    /* function init()
    {
        parent::init();
        //实例化文章数据库操作类
        $this->article = new ArticleModel();
        $this->multi = new multi();
    } */

    /**
     * 文章详情
     */
    /* public function indexAction($dir = '', $id = 0)
    {
        if ($id <> false && is_numeric($id)) {
            //去掉url末尾的字符，如：+
            $ret = explode('html', $_SERVER['REQUEST_URI']);
            if ($ret[1]) {
                Header("HTTP/1.1 301 Moved Permanently");
                Header("Location: " . rtrim($_SERVER['REQUEST_URI'], $ret[1]));
            }

            //获得该id对应的文章详情
            $data = $this->article->detail($id);

            //去除文章中的‘\’，否则js无法运行
            $data['content'] = stripslashes($data['content']);

            //关键词替换处理
            $data = $this->replaceKeywords($data);

            //关键词加内链
            $data['content'] = $this->addKeywordsUrl($data['content']);

            //Tag加链接
            if ($keywords = trim($data['keywords'])) {
                $data['tag'] = $this->keywordsAddUrl($keywords);
            }
            //获取分类名称
            $category = $this->base->category('en_key');
            $cid = isset($category[$dir]) ? intval($category[$dir]['catid']) : 0;
            $data['category'] = $category[$dir]['catname'];

            //添加点击量
            $hits = rand(30,400);
            //手机端
            $host = parse_url(PHONEURL, PHP_URL_HOST);
            if ($this->mobile || $host == $_SERVER['HTTP_HOST']) {
                if ($host != $_SERVER['HTTP_HOST']) {
                    header('Location:' . PHONEURL . SITEDIR . '/' . $dir . '/' . $id . '.html');
                    exit;
                }

                //推荐阅读
                $data['tjyd'] = $this->article->articleList(['catid' => $cid, 'attribute' => 1], 0, 4);

                //精华阅读
                $data['jhyd'] = $this->article->articleList(['catid' => $cid, 'attribute' => 1], 5, 8);
            } else {
                //获得最新文章的时间戳
                $newtime = $this->article->get_new_time();

                //最新文章本栏目最新10条
//                $data['zxwz'] = $this->article->articleList(['catid' => $cid, 'attribute' => [0, 1, 2]], 0, 15, 'inputtime DESC');
                $data['zxwz'] = $this->getListById(['id[>]' => $id,'catid' => $cid, 'attribute' => [0, 1, 2]], 0, 15);
//                echo $this->article->getLastQuery();
//                var_dump($data['zxwz1']);die;
                //全站最新30  底部
//                $data['zxzx'] = $this->article->articleList(['attribute' => [0, 1, 2]], 0, 30, 'inputtime DESC');
                $data['zxzx'] = $this->getListById(['id[>]' => $id,'attribute' => [0, 1, 2]], 0, 30, 'inputtime ASC');

                //上一篇下一篇
                $data['prenext'] = $this->article->preNext($data['inputtime'], ['catid' => $cid,'id'=>$id]);
            }

            //渲染模板
            $this->getView()->assign("categoryname", $data['category']);
            $this->getView()->assign("title", $data['title']);
            $this->getView()->assign("keywords", $data['title']);
            $this->getView()->assign("hits", $hits[0]['views']);
            $this->getView()->assign("description", $data['description'] ? strip_tags($data['description']) : mb_substr(strip_tags($data['content']), 0, 78) . '...');
            $this->getView()->assign("hover", $dir);
            $this->getView()->assign("data", $data);
            if ($this->mobile || $host == $_SERVER['HTTP_HOST']) {
                $this->getView()->display('./article/mobile.phtml');
                exit;
            }
        } else
            misc::show404();
    } */
    
    /**
     * 获取调用
     * @param type $keywords
     * @return string
     */
    /* public function getListById($cond, $start, $offset)
    {
        $data = $this->article->articleList($cond, $start, $offset, 'inputtime ASC');
        if(count($data) < $offset) {
            if(isset($cond['id[>]'])) {
                $cond['id[<]'] = $cond['id[>]'];
                unset($cond['id[>]']);
            }
            $dataL = $this->article->articleList($cond, $start, ($offset-count($data)), 'inputtime DESC');
            $data = array_merge($data,$dataL);
        }
        return $data;
    } */

    /**
     * 关键字添加链接
     * @param type $keywords
     * @return string
     */
    /* public function keywordsAddUrl($keywords)
    {
        $tag = "";

        //多个关键字
        if (strpos($keywords, ' ') && $keywords = explode(' ', $keywords)) {
            foreach ($keywords as $value) {
                if ($value) {
                    //keywords加链接，前面空格
                    $tag .= '   <a href="/' . SITEDIR . '/search/' . Pinyin::get($value) . '.html" >' . $value . '</a>  ';
                }
            }
        } else {
            //一个关键字
            $tag = '    <a href="/' . SITEDIR . '/search/' . Pinyin::get($keywords) . '.html" >' . $keywords . '</a>';
        }
        return $tag;
    } */

    /**
     * 相关文章
     * @param type $enKeyword
     */
    /* public function relevant($enKeyword, $count = 20)
    {
        $enKeyword = strpos($enKeyword, ' ') ? explode(' ', $enKeyword)[0] : trim($enKeyword);
        $data = [];
        if ($enKeywords = strpos($enKeyword, ' ')) {
          foreach ($enKeywords as $key => $value) {
          $data = $this->article->articleList(['LIKE' => ["en_keywords" => $value]], 0, $count);
          }
          } else {
        $data = $this->article->articleList(['LIKE' => ["en_keywords" => $enKeyword]]);

        //如果相关文章不够，从最新文章中填充
        if (count($data) < $count) {
            $new = $this->article->articleList([], 0, $count - count($data));
            $data = array_merge_recursive($data, $new);
        }
        //  }
        return $data;
    } */

    /* 关键词替换处理方法
     * @param array $data
     * @return array
     */

    /* public function replaceKeywords($data) {
        //获取文章关键词
        $content_words = $this->article->get_keywords();

        //文章关键词逐个替换
        foreach ($content_words as $ckeys) {

            if (strpos($data['keywords'], $ckeys['keywords']) !== false) {
                //页面头文件中关键词替换
                $data['keywords'] = preg_replace_callback("/" . $ckeys['keywords'] . "/", function($str) {
                    return $this->article->replace_keywords($str);
                }, $data['keywords']);
            }
            if (strpos($data['title'], $ckeys['keywords']) !== false) {
                //各标题中关键词替换
                $data['title'] = preg_replace_callback("/" . $ckeys['keywords'] . "/", function($str) {
                    return $this->article->replace_keywords($str);
                }, $data['title']);
            }
            if (strpos($data['description'], $ckeys['keywords']) !== false) {
                //文章描述关键词替换
                $data['description'] = preg_replace_callback("/" . $ckeys['keywords'] . "/", function($str) {
                    return $this->article->replace_keywords($str);
                }, $data['description']);
            }
            if (strpos($data['content'], $ckeys['keywords']) !== false) {
                //文章内容关键词替换
                $data['content'] = str_replace($ckeys['keywords'], $this->article->replace_keywords($ckeys['keywords']), $data['content']);
            }
        }

        return $data;
    } */

    /* 关键词加内部链接
     * @param string $key
     * @return string
     */

   /*  public function addKeywordsUrl($key) {
        //获取内链关键词
        $url_key = $this->article->get_key_datas();

        //对文章内容中的关键词追加链接
        foreach ($url_key as $nkeys) {

            if (strpos($key, $nkeys['keywords']) !== false) {

                $key = preg_replace_callback("/" . $nkeys['keywords'] . "/", function($str) {
                    $url_href = $this->article->replace_key_url($str);
                    return "<a href=" . $url_href['href_url'] . "  target=_blank >" . $str[0] . "</a>";
                }, $key, 1);
            }
        }

        return $key;
    } */

}
