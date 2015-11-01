<?php
header("Content-type: text/html; charset=utf-8");
set_time_limit(0);
ini_set('memory_limit', '2048M');
$lib = 'lib';

//cli模式下运行
if (defined('STDIN')) chdir(dirname(__FILE__));

if (realpath($lib) !== FALSE) $lib = realpath($lib) . '/';
// 站点根目录
define("APP_PATH", dirname(__DIR__));
//确保有一个斜杠
$lib = rtrim($lib, '/') . '/';


if (!is_dir($lib)) exit("lib路径不存在");


//lib绝对路径
define('LIBPATH', str_replace("\\", "/", $lib));

define('BASEPATH', str_replace('lib/', '', LIBPATH));

//抓取类
//require(LIBPATH . 'CURL.php');

//通用方法
require(LIBPATH . 'common.php');


require(LIBPATH . 'medoo.php');

$mysqldb = new medoo(['server' => '127.0.0.1', 'username' => 'root', 'password' => '', 'database_name' => 'lianchuangcms', 'port' => 3306]);


$dbhost = '192.168.118.46';
$dbuser = 'jc_gdzjdaily_hyzx';
$dbpass = 'jc_gdzjdaily_hyzx@!@#123';
$dbname = 'jc_gdzjdaily_hyzx';

$mssqldb = odbc_connect("Driver={SQL Server};Server=$dbhost;Database=$dbname", "$dbuser", "$dbpass");
