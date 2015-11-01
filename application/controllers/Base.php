<?php

if (!defined('APP_PATH'))
    exit('No direct script access allowed');

/**
 * controller基础类
 * User: kele
 * Date: 13-8-26
 * Time: 下午11:42
 */
class BaseController extends Yaf_Controller_Abstract {

    /**
     * 所有配置信息.
     * @var Yaf_Config_Ini
     */
    protected $config;
    //传递给模板的数据
    protected $data = [];
    //是否是手机端
    protected $mobile;
    //redis    
    protected $redis;
    //全局user
    protected $_user = ['uid' => 0, 'username' => ''];
    //一级栏目
    protected $navlist;
    function init() {
        // Assign application config file to this controller
        $this->config = Yaf_Application::app()->getConfig();
        //基础model
        $this->base = new BaseModel();
        $this->redis = Yaf_Registry::get('redis');
        $this->mobile = misc::checkmobile();

        $this->navlist = json_decode($this->redis->get('navlist'),true);
        if($this->navlist == false){
        	$this->navlist = $this->base->navlist();
        	$this->redis->setex('navlist', 3600, json_encode($this->navlist));
        }
        //导航
       	$this->getView()->assign('navlist', $this->navlist);
        $this->_initFrontConfig();
    }

    protected function _initFrontConfig() {
        header('Content-type:text/html;charset=utf-8');
        //获取网站配置信息
        $config = $this->base->get_option_info();
        //检查PC网站是否关闭
        if ($config['webstatus'] == 'off') {
            die('网站已经关闭');
        }

        //检查手机站点是否关闭
        if ($config['wapstatus'] == 'off' && $this->mobile) {
           die('手机网站已经关闭');
        }
        
        //获取时间偏差（用于老站搬迁时的时间差）
        $config['timeoffset'] = $config['timeoffset'] ? (int)$config['timeoffset'] : 0 ;
        $this->getView()->assign('config', $config);
    }

    function message($msg = '', $status = 0, $data = []) {
        header('Content-type:application/json;charset=utf-8');
        exit(json_encode(['status' => intval($status), 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE));
    }

    function form_submit() {
        return misc::form_submit(AUTHKEY);
    }

    // 检查是否登录
    function check_login($type = '') {
        if ($this->_user['uid'] <= 0) {
            if ($type == 'json') {
                return -10;
            }
            echo '<script> alert("对不起，您还没有登陆，请您前往登陆页面");window.location.href="/user/login"</script>';
            exit;
        } else {
            return 10;
        }
    }

    //检测用户发帖权限
    protected function check_forbidden_group($user) {
        if ($user['groupid'] == 7) {
            $this->message('对不起，您的账户被禁止发帖。');
        }
    }

    protected function check_user_exists($user) {
        if (empty($user)) {
            $this->message('用户不存在！可能已经被删除。');
        }
    }
	/**
	 *---------------------------------
	 * 获取远程地址数据
	 *---------------------------------
	 *
	 * @access	protected
	 */
	public function get_curl($request=array()) {
		$quest='<request>';
		$url=$request['url'];
		unset($request['url']);
		foreach($request as $k=>$v){
			$num = preg_match_all('/(or|and|exec|insert|select|delete|update|count|chr|mid|master|truncate|char|declare)/U',$v,$mad);
			if($num>0){
				exit("非法请求地址!!!");
			}
			$quest=$quest.'<'.$k.'>'.$v.'</'.$k.'>';
		}
		$quest=$quest.'</request>';
		$url = 'http://service.guahao.cn/hrs/rest/'.$url.'/';
		$headers['partner'] = '3746002';	//appid
		//前秘钥
		$headers['sign'] = md5('lc1118@service^*&^$~@guahao'.$quest);
		//$headers['sign'] = md5('lc1118@20150804guahao'.$quest);
		$headers['Host'] = '192.168.10.88:888';
		$headers['Content-Length'] = strlen($quest);
		$headers['Expect'] = '100-continue';
		$headers['Connection'] = 'Keep-Alive';
		$headers['Content-type'] = '';//'application/json';
		$headers['Accept'] = '';	
		$headerArr = array(); 
		foreach( $headers as $n => $v ) { 
			$headerArr[] = $n .':' . $v;  
		}
		$ch = curl_init($url);
		$res= curl_setopt ($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $quest);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headerArr);
		$result=simplexml_load_string(curl_exec($ch));
		curl_close($ch);
		$arr = $this->object_to_array($result);
		return $arr;
	}
	
	/*  挂号网接口*/
	public function get_curlgh($request=array()) {
	     $quest='<request>';
	     $url=$request['url'];
	     unset($request['url']);
	    foreach($request as $k=>$v){
	      $quest=$quest.'<'.$k.'>'.$v.'</'.$k.'>';
	    }
	    $quest=$quest.'</request>';
	    $url = 'lc1118@service^*&^$~@guahao'.$url.'/';
	    $headers['partner'] = '3746002';
	    $headers['sign'] = md5('lc1118@service^*&^$~@guahao'.$quest);
	    $headers['Host'] = '192.168.118.81:8092';//'192.168.10.88:888';
	    $headers['Content-Length'] = strlen($quest);
	    $headers['Expect'] = '100-continue';
	    $headers['Connection'] = 'Keep-Alive';
	    $headers['Content-type'] = '';//'application/json';
	    $headers['Accept'] = '';  
	    $headerArr = array(); 
	    foreach( $headers as $n => $v ) { 
	      $headerArr[] = $n .':' . $v;  
	    }
	     $ch = curl_init($url);
	  
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $quest);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER,$headerArr);
	    $result=simplexml_load_string(curl_exec($ch));
	    curl_close($ch);
	    if(empty($result)){
	      echo '404 not found';
	      return;
	    }
	    $result=get_object_vars($result);
	    return $result;
    }
	/*对象转数组*/
	protected function object_to_array($result){
		$_arr = is_object($result) ? get_object_vars($result) : $result;
		foreach ($_arr as $key => $val){
			$val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
			$arr[$key] = $val;
		}
		return $arr;
	}
}
