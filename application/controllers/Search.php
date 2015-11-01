<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

class SearchController extends BaseController {

    /**
     * 文章列表页
     * dir----dirname
     */
   /*  public function IndexAction($keywords = '', $page = 1) {
        //实例化文章数据库操作类
        $article = new ArticleModel();
        //关键字
        $keywords = core::safe_str(rtrim($keywords));

        //第几页
        $page = intval($page);
        //每页显示条数
        $offset = 50;

        $start = ($page - 1) * $offset;

        $data['data'] = $article->searchList(['LIKE' => ["en_keywords" => $keywords]], $start, $offset);
        if(empty($data['data'])) {
            misc::show404();
        }
        $total = $article->get_title_count(['LIKE' => ["en_keywords" => $keywords]]);

        $totalpage = ceil($total / $offset);

        //超过一页显示分页
        if ($totalpage > 1) {
            $data['page'] = (new multi())->getSubContent('/' . SITEDIR . '/search/' . $keywords . '/default_', '.html', $totalpage, $page, 46, ' target="_self" ', '', '', 'no');
        }

        //最新
        $data['new'] = $article->articleList(['attribute'=>[0,1,2]], 0, 30);

        //取tag关键字中文
        $tag = $article->tag($data['data'],$keywords);

        $this->getView()->assign("data", $data);
        $this->getView()->assign("totalpage", $totalpage);
        $this->getView()->assign("total", $total);
        $this->getView()->assign("page", $page);
        $this->getView()->assign("title", '关键词“' . $tag . '”');
        $this->getView()->assign("keywords", $tag);
        $this->getView()->assign("description", '关键词“' . $tag . '”的相关文章');
    } */

}
