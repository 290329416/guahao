<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: kele
 * Date: 11/21/14
 * Time: 9:31 AM
 * 全站api调用统一接口
 */
class ApiController extends Yaf_Controller_Abstract {

    /* function init() {
        $this->memcache = Yaf_Registry::get('memcache');
        //数据库
        $this->db = Yaf_Registry::get('db');
        // 关闭自动渲染 
        Yaf_Dispatcher::getInstance()->disableView();
    }

    function indexAction() {
        $client_data = file_get_contents("php://input");
        var_dump($client_data);
        exit(0);
    }
 */
    /*
     * 文章的浏览量
     */

   /*  function clickAction() {

        if ('POST' == $this->getRequest()->method) {
            //文章id
            $id = $this->getRequest()->getPost('id', 0);
            //栏目id
            $catid = intval($this->getRequest()->getPost('catid', 0));
            if (is_numeric($id)) {
                $memkey = MEMPREFIX . 'article:hits' . $id;
                $views = intval($this->memcache->get($memkey));
                if ($views == false) {//memcache没有值或者失效
                    $views = intval($this->db->get('hits', 'views', ['aid' => $id]));
                    $views++;
                    echo json_encode(['hit' => $views]);
                    // fastcgi_finish_request();
                    if ($views == 1)
                        $this->db->insert('hits', ['aid' => $id, 'views' => $views, 'catid' => $catid]);
                    else
                        $this->db->update('hits', ['views' => $views], ['aid' => $id]);
                    $this->memcache->set($memkey, $views, 0, 3600 * 24 * 29);
                } else {
                    $views++;
                    echo json_encode(['hit' => $views]);
                    //  fastcgi_finish_request();
                    //20次入库一次
                    if ($views % 20 == 0)
                        $this->db->update('hits', ['views' => $views], ['aid' => $id]);
                    $this->memcache->set($memkey, $views, 0, 3600 * 24 * 29);
                }
                return $views;
            }
        }
    } */

    //输出栏目列表
    /* function categoryAction() {
        $memkey = MEMPREFIX . 'category';
        $data = $this->memcache->get($memkey);
        //$data = false;
        if ($data == false) {
            $data = [];
            $temp = $this->db->select('category', [
                'catid',
                'catname',
                'catpath',
                    ], [
                'parentid' => 7,
                "ORDER" => "orderid DESC",
                "LIMIT" => 200
                    ]
            );
            foreach ($temp as $v)
                $data[$v['catid']] = $v;
            //缓存一小时
            $data <> false && $this->memcache->set($memkey, $data, MEMCACHE_COMPRESSED, 3600 * 24);
        }
        return $data;
    } */

    //article入库
   /*  function articleAction() {
        //入库
        if ('POST' == $this->getRequest()->method) {
            //标题
            $title = $this->getRequest()->getPost('title', '');
            $title <> false && $title = trim(misc::remove_nbsp(mb_convert_encoding($title, "UTF-8")));
            //媒体id
            $mtidlist = $this->getRequest()->getPost('mtidlist', 0);
            $catid = $this->getRequest()->getPost('catid', 0);
            //关键词
            $keywords = $this->getRequest()->getPost('keyword', '');
            $keywords != false && $keywords = trim(misc::remove_nbsp(mb_convert_encoding($keywords, "UTF-8")));
            //关键字(如果没写关键字，在标题中取。如果没取到，标题作为关键词)
            $keywords = empty($this->getRequest()->getPost('keyword', '')) ?
                    implode('   ', misc::getKeywords($title)) :
                    $this->getRequest()->getPost('keyword', '');
            $keywords = empty($keywords) ? $title : $keywords;

            //创建时间
            $createdate = $this->getRequest()->getPost('createdate', 0);
            $createdate = $createdate ? strtotime($createdate) : $_SERVER['time'];
            //token
            $token = $this->getRequest()->getPost('token', '');
              if ($token == false) {
              echo json_encode(['msg' => '缺少参数', 'errcode' => -100]);
              exit;
              }
            //内容
            $content = $this->getRequest()->getPost('content', '');
            if ($content <> false) {
                $content = mb_convert_encoding($this->getRequest()->getPost('content', ''), "UTF-8");
                $descripion = trim(misc::remove_nbsp(mb_substr(strip_tags($content), 0, 78, 'UTF-8')));
            }


            if ($title == false) {
                echo json_encode(['msg' => '缺少标题参数', 'errcode' => -401], JSON_UNESCAPED_UNICODE);
                exit;
            }elseif(  $catid == false ){
                echo json_encode(['msg' => '缺少栏目参数', 'errcode' => -402], JSON_UNESCAPED_UNICODE);
                exit;
            }elseif($content == false){
                echo json_encode(['msg' => '缺少内容参数', 'errcode' => -403], JSON_UNESCAPED_UNICODE);
                exit;
            }
            $category = $this->categoryAction();
            $id = $this->getRequest()->getPost('id', '');
            if (!isset($category[$catid])) {
                echo json_encode(['msg' => 'catid错误', 'errcode' => -300], JSON_UNESCAPED_UNICODE);
                exit;
            }
            if ($id <> false) {//修改
                $updateid = $this->db->update('news', [
                    'title' => $title,
                    'catid' => $catid,
                    'keywords' => $keywords,
                    'description' => $descripion,
                    'updatetime' => $_SERVER['time'],
                    'status' => 99
                        ], [
                    'id' => $id,
                        ]
                );
                $this->db->update('news_data', [
                    'content' => addslashes(stripslashes($content))
                        ]
                        , [
                    'id' => $id,
                        ]
                );
                if ($updateid >= 0) {
                    $memkey = MEMPREFIX . 'article:detail' . $id;
                    $this->memcache->delete($memkey);
                    $data = [
                        'msg' => '修改成功',
                        'errcode' => 0,
                        'url' => BASEURL . SITEDIR . '/' . $category[$catid]['catpath'] . '/' . $id . '.html',
                        'classid' => $catid,
                        'id' => $id
                    ];
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                } else
                    echo json_encode(['msg' => '修改失败', 'errcode' => -300], JSON_UNESCAPED_UNICODE);
            } else {//增加
                $insertid = $this->db->insert('news', [
                    'title' => $title,
                    'catid' => $catid,
                    'keywords' => $keywords,
                    'description' => $descripion,
                    'inputtime' => $createdate,
                    'status' => 99,
                        ]
                );
                if ($insertid <> false) {
                    $this->db->insert('news_data', [
                        'id' => $insertid,
                        'content' => addslashes(stripslashes($content))
                            ]
                    );

                    //TAG关键字功能
                    $this->db->insert('keywords_data', [
                        'aid' => $insertid,
                        'cid' => $catid,
                        'description' => $description,
                        'inputtime' => $createdate,
                        'keywords' => $keywords,
                        'title' => $title,
                        'keyword_path' => misc::get_first_pinyin($keywords),
                        'catpath' => $category[$catid]['catpath']
                            ]
                    );

                    $data = [
                        'msg' => '共发布成功1篇',
                        'errcode' => 0,
                        'url' => BASEURL . SITEDIR . '/' . $category[$catid]['catpath'] . '/' . $insertid . '.html',
                        'id' => $insertid
                    ];
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(['msg' => '添加失败', 'errcode' => -300], JSON_UNESCAPED_UNICODE);
                }
            }
        } else {
            misc::show404();
        }
    } */
    
    //敏感词文章删除
   /*  function deleteAction()
    {
        if ('POST' == $this->getRequest()->method) {
            $id = $this->getRequest()->getPost('id', '');
            if ($id <> false) {//修改
                $updateid = $this->db->update('news', [
                        'status' => 5
                    ], [
                        'id' => $id,
                    ]
                );
                if (count($updateid) > 0) {
                    foreach($id as $v){
                        $memkey = MEMPREFIX . 'article:detail' . $v;
                        $this->memcache->delete($memkey);
                        $data = [
                            'msg' => '删除成功',
                            'errcode' => 0,
                            'url' => BASEURL . SITEDIR . '/' . $v . '.html',
                        ];
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                } else{
                    echo json_encode(['msg' => '删除失败', 'errcode' => -381], JSON_UNESCAPED_UNICODE);
                }
            }
        }
    } */


}
