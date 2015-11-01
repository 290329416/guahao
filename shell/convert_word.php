<?php
ini_set('odbc.defaultlrl', '1024000');
require 'conf.php';
for ($i = 0; $i <= 200; $i++) {
    $newmysqldb = new medoo(['server' => '127.0.0.1', 'username' => 'root', 'password' => '', 'database_name' => 'lianchuangcms', 'port' => 3306]);

    $maxid = $newmysqldb->max('news', 'id');
    $maxid == false && $maxid = 0;
//文章表
    $sql = 'SELECT TOP(5000) ID,ClassID,Title,keyword,OrderID,Attribute,UserID,CreateDate,UpdateDate,CONVERT(text,Content) as Content,DefaultPic,FromWeb,filename FROM Word where ID >' . $maxid . ' ORDER BY ID ASC';
    $exec = odbc_exec($mssqldb, $sql);
    while ($row = (odbc_fetch_array($exec))) {
        $content = trim(mb_convert_encoding(trim($row['Content']), "UTF-8", "GBK"));
        $insertid = $newmysqldb->insert('news', [
                'id' => str_replace('.0', '', $row['ID']),
                'catid' => str_replace('.0', '', $row['ClassID']),
                'title' => trim(str_replace("&nbsp;", '', remove_nbsp(mb_convert_encoding($row['Title'], "UTF-8", "GBK")))),
                'keywords' => trim(remove_nbsp(mb_convert_encoding($row['keyword'], "UTF-8", "GBK"))),
                'description' => trim(str_replace("&nbsp;", '', remove_nbsp(mb_substr(strip_tags($content), 0, 78, 'UTF-8')))),
                'listorder' => str_replace('.0', '', $row['OrderID']),
                'attribute' => intval($row['Attribute']),
                'uid' => str_replace('.0', '', $row['UserID']),
                'username' => '',
                'url' => '',
                'islink' => 0,
                'inputtime' => strtotime($row['CreateDate']),
                'updatetime' => strtotime($row['UpdateDate']),
                'status' => 99
            ]
        );
        $newmysqldb->insert('news_data', [
                'id' => str_replace('.0', '', $row['ID']),
                'content' => addslashes(stripslashes($content)),
                'defaultpic' => $row['DefaultPic'],
                'fromweb' => $row['FromWeb'],
                'filename' => $row['filename'],
            ]
        );
        echo 'insert article success id is ' . $row['ID'] . PHP_EOL;
    }
}

function remove_nbsp($str)
{
    $str = mb_ereg_replace('^(　| )+', '', $str);
    $str = mb_ereg_replace('(　| )+$', '', $str);
    $str = mb_ereg_replace('　　', "\n　　", $str);
    $order = array("\r\n", "\n", "\r");
    $replace = '<br/>';
    $str = str_replace($order, $replace, $str);
    return $str;
}