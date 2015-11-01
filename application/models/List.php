<?php

/**
 * 文章数据库操作类
 *
 * User: zhaoqingyang@lianchuangbrothers.com
 * Date: 15-7-27
 * Time: 16:03
 */
class ListModel extends BaseModel
{

    /**
     * 初始化
     */
    function __construct()
    {
        parent::__construct();
    }
	
    /**
     * 获取疾病首页二级级栏目
     *
     * @param string id 	疾病的一级ID
     * @return array;	科室及其子类
     */
    public function detail($id)
    {
		$catlist = $this->db->select('category',['catid','catname','catpath'],['parentid' => $id]);
		for($i=0;$i<count($catlist);$i++){
			$article = $this->db->select('news',['id','catid','title','keywords','description'],['catid' => $catlist[$i]['catid'],"ORDER" => "inputtime DESC",'LIMIT' => [0,4]]);
			array_push($catlist[$i],$article);
		}
        return $catlist;
    }
    /**
     * 获取疾病首页二级栏目的文章
     *
     * @param string array 	医院名字与ID的数组
     * @return array;	科室及其子类
     */
    public function Article($array,$limit=10)
    {   
        
    	$article = array();

    	for($i=0;$i<count($array);$i++){
                $catlist = $this->db->select('category',['catid','catpath'],['parentid' => $array[$i]['catid']]);
                //$catlist = $this->db->select('category',['catid','catname','catpath'],['parentid' => 0,"ORDER" => "catid DESC"],"LIMIT"=>13);
                $id = array();
                $catid = array();

                for($n=0;$n<count($catlist);$n++){
                    $id[] = $catlist[$n]['catid'];
                    $catid[$catlist[$n]['catid']] = $catlist[$n]['catpath'];
                }
                if(count($array)<=1){
                    $article = $this->db->select('news',['id','catid','title','inputtime'],['catid' => $id ,"ORDER" => "inputtime DESC",'LIMIT' => [0,$limit]]); 
                }else{
                    $article[$array[$i]['Disease']] = $this->db->select('news',['id','catid','title','inputtime'],['catid' => $id ,"ORDER" => "inputtime DESC",'LIMIT' => [0,$limit]]); 
                }
                
                for($m=0;$m<$limit;$m++){
                    if(count($array)<=1){
                        $article[$m]['catpath'] = $catid[$article[$m]['catid']];
                    }else{
                        $article[$array[$i]['Disease']][$m]['catpath'] = $catid[$article[$array[$i]['Disease']][$m]['catid']];
                    }
            }
    	}
		return $article;
    }
	/**
     * 获取疾病列表页信息 
     *
     * @param catid 文章ID	total总数	page当前页  limit每页总条数
     * @return array;	科室文章
     */
    public function conlist($catid,$total,$page=0,$limit=16)
    {
		if(($page-1)*$limit > $total){
			$page = floor($total/$limit);
		}
		if($page<1){
			$page = 1;
		}
		$first = ($page-1)*$limit;
        $list = $this->db->select('news', ['id','title','inputtime'], ['catid' => $catid,"ORDER" => "inputtime DESC","LIMIT" => [$first,$limit]]);
        
        
        return $list;
    }
    //获取URL时用
	public function shujuurl($catid,$name,$path){
        $list = $this->db->select('news', 'id', ['catid' => $catid,"ORDER" => "inputtime DESC"]);
        $url = '';
        for($i=0;$i<count($list);$i++){
            $url .= "http://guahao.health.sohu.com/$name/$path/".$list[$i].'.html'.'<br/>';
        }
        print_r($url);
        exit;
    }
	/**
     * 获取列表页分页
     *
     * @param string 	分页  name 一级科室	path 二级科室  total总条数	page当前页	 limit 每页数据
     * @return string;	分页url
     */
	public function listpage($name,$path=null,$total,$page=0,$limit=16){
		if(($page-1)*$limit > $total){
			$page = ceil($total/$limit);
		}
		if($page<=1){
			$page = 1;
		}
        $num = ceil($total/$limit);
        if($page<=5){
            $n = 1;
        }elseif($page>5 && $page<=$num-5){
            $n = $page - 5;
        }elseif($page>$num-5){
            $n = $num-10<0?1:$num-10;
        }
		if($num>1){
			$pageurl = "<span><a href='/".$name."/".($path?$path.'/':'')."?page=1"."'>首页</a></span>&nbsp;&nbsp;";
			for($i=$n;$i<= ($num<$n+10?$num:$n+10);$i++){
				if($i==$page){
					$pageurl .= "<a href='/".$name."/".($path?$path.'/':'')."?page=".$i."'><font color='#FF0000'><b>".$i."</b></font></a>&nbsp;&nbsp;";
				}else{
					$pageurl .= "<a href='/".$name."/".($path?$path.'/':'')."?page=".$i."'>".$i."</a>&nbsp;&nbsp;";
				}
				if($i == ($num<$n+10?$num:$n+10)){
					$pageurl .= "<a href='/".$name."/".($path?$path.'/':'')."?page=".$num."'>尾页</a>&nbsp;&nbsp;";
				}
			}
		}
		return $pageurl;
	}
	/**
     * 获取导航nav信息
     *
     * @param string 	
     * @return array;
     */
	public function nav($id){
		if($id){
			$navlist = $this->db->select('category',['catname','catpath'],['parentid' => $id]);
		}
		return $navlist;
	}
    /**
     * 获取最新文章
     *
     * @param string  limit 获取的问题数目
     * @return array;
     */
    public function new_article($limit){
        $list = $this->db->select('news',['id','catid','title'],["ORDER" => "inputtime DESC","LIMIT" => $limit]);
        return $list;
    }
    /**
     * 获取一级栏目的最新文章
     *
     * @param string  limit 获取的问题数目
     * @return array;
     */
    public function one_content($navid,$limit=10){
        $article = $this->db->select('news',['id','catid','title'],['catid' => $navid ,"ORDER" => "inputtime DESC",'LIMIT' => $limit]); 
        return $article;
    }

    public function num($name){
        $article = array();
        $id = array();
        $oneid = $this->db->get('category',['catid','catpath'],['catpath' => $name]);
        $twoid = $this->db->select('category',['catid','catpath'],['parentid' => $oneid]);
        for($n=0;$n<count($twoid);$n++){
            $id[] = $twoid[$n]['catid'];
        }
        $num = $this->db->count('news',['catid' => $id]); 
        
        return $num;
    }

    /**
     * 获取某个栏目下随机多少篇文章
     *
     * @param string  catid 栏目的ID
     * @param string  limit 获取的问题数目
     * @return array;
     */
    public function rand_news($catid,$limit){
        $first = rand(1,20);
        $article = $this->db->select('news',['id','catid','title','inputtime'],['catid' => $catid ,'LIMIT' => [$first,$limit]]);
        return $article;
    }

    public function getid($catid){
        $numnavall = json_decode($this->redis->get('numnavall'),true);
        foreach($numnavall as $val){
            if($val['parentid'] == $catid){
                $idlist[] = $val['catid'];
            }
        }
        return $idlist;
    }
}

