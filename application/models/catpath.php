<?php

/**
 * 文章数据库操作类
 *
 * User: zhaoqingyang@lianchuangbrothers.com
 * Date: 15-7-27
 * Time: 16:03
 */
class catpathModel extends BaseModel
{
    /**
     *获得每个栏目的path路径
     *
     *@param int $pid 父类id
     *@param array $result 存放结果集
     *@param int $leve 遍历深度
     *@return array
     */
    public function catelist($pid=0, &$result=[], $leve=0, $path){
        $where['parentid'] = $pid;
        $res = $this->db->select('category', '*', $where);

        //深度加一
        $leve++;

       foreach($res as $row){
                if($row['parentid'] == 0){
                    $row['name'] = '/' . $row['catpath'] . '/';
                }else{
                    $row['name'] =  "/$path/{$row['catpath']}/";
                    $row['leve'] = $leve;
                }
            //以栏目名为键
            $result[$row['catpath']] = $row;
            if($row['leve']==2){
                $result[$path]['leve'] = 1;
            }
            $this->catelist( $row['catid'], $result, $leve, $row['catpath']);
            
        }

        return $result;
    }



    /**
     *处理栏目关系
     *@param int $pid 父类id
     *@return array
     */
    public function dealList($pid = 0){

        $str = [];
        foreach($this->catelist($pid) as $key){

            $str[$key['catid']] .= $key['name'];
        }

        return $str;
    }


    /**
     *通过栏目id获取栏目路径对应关系
     *
     *例如：catid=345678,则返回对应的路径为xpth/hslx/
     *
     *@param int $pid 父类id
     *@return string
     */
    public function get_catpath($catid) {
        
        $key = 'guahao_souhu_catlist';
        $catpath = $this->redis->get($key);
        if($catpath == FALSE){
            $catpath = $this->dealList();
            $this->redis->setex($key, 35680, $catpath);
        }

        return $catpath[$catid];

    }

    //获取一级栏目名称
    public function navlist($limit=14){
        $catlist = json_decode($this->redis->get('catidcatpathcatname'.$limit),true);
        if($catlist == false){
            $catlist = $this->db->select('category',['catid','catpath','catname'],['parentid' => 0,"ORDER" => "catid DESC",'LIMIT'=>$limit]);
            $this->redis->setex('catidcatpathcatname'.$limit, 7200 , json_encode($catlist));
        }
        return $catlist;
    }


    //获取一级栏目catid
    public function navidlist($limit=14){
        $catlist = json_decode($this->redis->get('idlist'.$limit),true);
        if($catlist == false){
            $catlist = $this->db->select('category','catid',['parentid' => 0,"ORDER" => "catid DESC",'LIMIT'=>$limit]);
            $this->redis->setex('idlist'.$limit, 7200 , json_encode($catlist));
        }
        return $catlist;
    }
    //获取一级栏目下的文章
    public function Article($array,$limit=10)
    {   
        for($i=0;$i<count($array);$i++){
            $idmd5 .= $array[$i]['catid'];
        }
        $article = array();
        $article = json_decode($this->redis->get(md5($idmd5)),true);
        if($article == false){
            for($n=0;$n<count($array);$n++){
                $article[$n] = $this->db->select('news',['id','catid','title','inputtime'],['catid' => $array[$n]['catid'] ,"ORDER" => "inputtime DESC",'LIMIT' => [0,$limit]]); 
                $article[$n]['catpath'] = $array[$n]['catpath'];
                $article[$n]['catname'] = $array[$n]['catname'];
            }
            $this->redis->setex(md5($idmd5), 7200 , json_encode($article));
        }
        
        return $article;
    }

    /**
     *获得每个栏目的path路径
     *
     *@param int $pid 父类id
     *@param array $result 存放结果集
     *@param int $leve 遍历深度
     *@return array
     */
    public function numkeycat($pid=0, &$result=[], $leve=0, $path){
        $where['parentid'] = $pid;
        $res = $this->db->select('category', '*', $where);

        //深度加一
        $leve++;

       foreach($res as $row){
                if($row['parentid'] == 0){
                    $row['name'] =  $row['catpath'] . '/';
                }else{
                    $row['name'] =  "$path/{$row['catpath']}/";
                }
            //以栏目ID为键
            $result[$row['catid']] = $row;
            $this->numkeycat( $row['catid'], $result, $leve, $row['catpath']);
        }

        return $result;
    }

}

