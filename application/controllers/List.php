<?php

/**
 * 关键字控制类
 *
 * User: zhaoqingyang@lianchuangbrothers.com
 * Date: 15-7-27
 * Time: 10:02
 */
class ListController extends BaseController
{

    /**
     * 初始化所有的栏目信息,以栏目catpath为键
     */
    protected $navall;

	/**
     * 以栏目catid为键
     */
    protected $numnavall;

    function init()
    {
        parent::init();
		$this->navall = json_decode($this->redis->get('navall'),true);
		if($this->navall == false){
			$object = new catpathModel();
			$this->navall = $object->catelist();
			$this->redis->setex('navall',36000,json_encode($this->navall));
		}
		$this->numnavall = json_decode($this->redis->get('numnavall'),true);
		if($this->numnavall == false){
			$object = new catpathModel();
			$this->numnavall = $object->numkeycat();
			$this->redis->setex('numnavall',36000,json_encode($this->numnavall));
		}
    }
	/**
     * 文章首页
     */
    public function articleAction($name){
    	$name = htmlspecialchars($name);
    	$leve = $this->navall["$name"]['leve'];
		$list = new ListModel();
    	if($leve == 1){
		//获取当前一级栏目的ID
		$id = $list->get('category', ['catid','catname','catpath','description','keyword'], ['catpath' => $name]);
		$id['title'] = str_replace(',',"_",$id['keyword']);

		//导航位置标签
		$nav = $list->nav($name);
		//首页内容数据
		$catlist = $list->detail($id['catid']);
		//友情链接
		$friendurl = "<li><a href='/$name/'>";
		$friendurl .= str_replace(',',"</a></li><li><a href=/$name/ >",$id['keyword']);
		$friendurl .= '</a></li>';
		
		$this->getView()->assign('id',$id);
		$this->getView()->assign('catlist',$catlist);
		$this->getView()->assign('nav',$nav);
		$this->getView()->assign('friendurl',$friendurl);
		}elseif(empty($leve)){
			$catid = $this->navall["$name"];
			if(!empty($catid)){
				$page = intval($_GET['page']);
				$catid['title'] = str_replace(',',"_",$catid['keyword']);
				//获取文章的总数
				$total = $list->count('news',['catid'=>$catid['catid']]);
				//---------------------------------------------二级导航位置标签没有
				$num = ceil($total/24);
				//友情链接
				$friendurl = "<li><a href='/{$name}/'>";
				$friendurl .= str_replace(',',"</a></li><li><a href=/{$name}/ >",$catid['keyword']);
				$friendurl .= '</a></li>';
				//文章列表
				$conlist = $list->conlist($catid['catid'],$total,$page,24);
				for($i=0;$i<count($conlist);$i++){
				$conlist[$i]['url'] = "/{$name}/{$conlist[$i]['id']}.html";
				}
				//文章分页
				$listpage = $list->listpage($name,null,$total,$page,24);

				//获取当前栏目随机11篇文章
				$article = $list->rand_news($catid['catid'],11);


				//最新的30条文章
				$article_s = $list->new_article(30);
			}
			$this->getView()->assign('conlist',$conlist);
			$this->getView()->assign('total',$total);
			$this->getView()->assign('num',$num);
			$this->getView()->assign('article',$article);
			$this->getView()->assign('page',$page);
			$this->getView()->assign('friendurl',$friendurl);
			$this->getView()->assign('article_s',$article_s);
			$this->getView()->assign('catkey',$this->numnavall);
			$this->getView()->assign('catid',$catid);
			$this->getView()->assign('listpage',$listpage);
			$this->getView()->display('./list/notwolist.phtml');
			exit;
		}
    }
	/**
     * 文章列表页
     */
	public function listAction($name,$path=null){
		$list = new ListModel();
		$page = intval($_GET['page']);
		//获取当前一级栏目的ID
		$oname = $list->get('category',['catid','catname','catpath'],['catpath' => $name]);
		//获取当前二级栏目的ID
		$catid = $list->get('category',['catid','catname','catpath','description','keyword'],['AND'=>['parentid'=>$oname['catid'],'catpath' => $path]]);
		if(!empty($catid)){
			$catid['title'] = str_replace(',',"_",$catid['keyword']);
			//获取文章的总数
			$total = $list->count('news',['catid'=>$catid['catid']]);
			//二级导航位置标签
			$nav = $list->nav($name);
			//最大页数
			$num = ceil($total/24);
			//友情链接
			$friendurl = "<li><a href='/$name/$path/'>";
			$friendurl .= str_replace(',',"</a></li><li><a href=/$name/$path/ >",$catid['keyword']);
			$friendurl .= '</a></li>';
			//获取URL时用
			// $list->shujuurl($catid['catid'],$name,$path);
			//文章列表
			$conlist = $list->conlist($catid['catid'],$total,$page,24);
			
			//文章分页
			$listpage = $list->listpage($name,$path,$total,$page,24);
			for($i=0;$i<count($conlist);$i++){
				$conlist[$i]['url'] = "/{$name}/{$path}/{$conlist[$i]['id']}.html";
			}
			
			//获取一级栏目最新的文章
			$array = array(array('Disease'=>$oname['catname'],'catid'=>$oname['catid']));
			//获取列表页二级栏目的前11条文章
			$article = $list->Article($array,11);
			//最新的30条文章
			$article_s = $list->new_article(30);
			$this->getView()->assign('conlist',$conlist);
			$this->getView()->assign('total',$total);
			$this->getView()->assign('num',$num);
			$this->getView()->assign('article',$article);
			$this->getView()->assign('page',$page);
			$this->getView()->assign('friendurl',$friendurl);
			$this->getView()->assign('article_s',$article_s);
			$this->getView()->assign('catkey',$this->numnavall);
			$this->getView()->assign('oname',$oname);
			$this->getView()->assign('tname',$catid);
			$this->getView()->assign('listpage',$listpage);
			$this->getView()->assign('nav',$nav);
		}
    }
	/**
     * 文章详情页
     */
	public function detailsAction($name,$path=null,$id=null){
		$list = new ListModel();
		if($id == false){
			$id = $path;
		}
		//获取当前一级栏目
		// $oname = $list->get('category',['catid','catname','catpath','description','keyword'],['catpath' => $name]);
		//获取当前二级栏目
		// $tname = $list->get('category',['catid','catname','catpath','keyword'],['AND'=>['parentid'=>$oname['catid'],'catpath' => $path]]);
		//获取文章标题

		$title = $list->get('news',['id','catid','title','keywords','description','inputtime'],['id'=>$id]);
		$tname = $this->numnavall[$title['catid']];
		if(empty($tname)){
			misc::show404();
		}
		$two_id = $tname['catid'];
		//友情链接
		$friendurl = "<li><a href=/{$tname['name']}>";
		$friendurl .= str_replace(',',"</a></li><li><a href={$tname['name']} >",$tname['keyword']);
		$friendurl .= '</a></li>';

		//如果是二级栏目下的文章获取二级栏目的相关信息
		if($tname['parentid'] != 0){
			$oname = $this->numnavall[$tname['parentid']];
			//二级导航位置标签
			$nav = $list->nav($oname['catid']);
			//获取当前一级栏目的所有二级栏目catid
			$two_id = $list->getid($tname['parentid']);
			//友情链接
			$friendurl = "<li><a href={$oname['name']}>";
			$friendurl .= str_replace(',',"</a></li><li><a href={$oname['name']} >",$oname['keyword']);
			$friendurl .= '</a></li>';
		}
		$title['keywords'] = str_replace(' ',',',$title['keywords']);
		if(empty($title['keywords'])){
			$title['keywords'] = $title['title'];
		}
		if(!empty($title)){
			//获取文章主体内容
			$content = $list->get('news_data',['id','content'],['id'=>$id]);
			//截取P标签
			$str = "<P style=".'\"'.'color:#FFFFFF'.'\">';
			$num = strpos($content['content'],$str);
			if($num){
				$content['content'] = substr($content['content'],0,$num);
			}
			//当前栏目下的最新文章
			$two_title = $list->select('news',['id','title'],['catid'=>$tname['catid'],"ORDER" => "inputtime DESC","LIMIT" => 9]);
			//最新的30条文章
			$article_s = $list->new_article(30);
			//最新的栏目的文章
			$one_content = $list->one_content($two_id,10);

			$this->getView()->assign('title',$title);
			$this->getView()->assign('content',$content);
			$this->getView()->assign('catkey',$this->numnavall);
			$this->getView()->assign('article_s',$article_s);
			$this->getView()->assign('friendurl',$friendurl);
			$this->getView()->assign('two_title',$two_title);
			$this->getView()->assign('one_content',$one_content);
			$this->getView()->assign('oname',$oname);
			$this->getView()->assign('tname',$tname);
			$this->getView()->assign('nav',$nav);
		}
	}

	//测试方法代码
	public function ceshiAction($name){

		//phpinfo();
    	// $object = new catpathModel();
    	// $navlist = json_decode($this->redis->get('catelist'),true);
    	// if($navlist == false){
    	// 	$navlist = $object->catelist();
    	// 	$this->redis->setex('catelist',100,json_encode($navlist));
    	// }

  //   	$nav = $object->navlist('14');
  // 	$list = $object->Article($nav);
  //   	print_r(count($list[0]));
  //   	echo '<br>';
  //   	print_r($list);
  //   	exit;
    }
}