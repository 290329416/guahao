<?php

/**
 * 关键字控制类
 *
 * User: zhangxudong@lianchuangbrothers.com
 * Date: 14-11-14
 * Time: 10:02
 */
class TestController extends BaseController
{

    /**
     * 初始化
     */
   /*  function init()
    {
        parent::init();
        $this->keyword = new TestModel();
    }

    function indexAction(){
        set_time_limit(5200);
        $keywords_list = $this->keyword->get_keywords(["AND"=>["keywords[!]"=>""]]);
        echo count($keywords_list);
        if($keywords_list){
            foreach($keywords_list as $k=>$v){
                $data = array();
                $data['aid'] = $v['id'];
                $data['cid'] = $v['catid'];
                $data['title'] = $v['title'];
                $data['description'] = $v['description'];
                $data['keywords'] = $v['keywords'];
                $data['inputtime'] = $v['inputtime'];
                //获取catpath
                $data['catpath'] = $this->keyword->get_catpath(["catid"=>$v['catid']]);
                $data['keyword_path'] = misc::get_first_pinyin($v['keywords']);
                $this->keyword->insert_keywords_data($data);
                unset($keywords_list[$k]);
                unset($data);
            }

            echo '<hr/>ok';exit;
        }
    } */



}