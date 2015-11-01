<?php
require 'conf.php';
require 'table.php';
//附件表
$sql = 'select * from UploadFile';
$exec = odbc_exec($mssqldb, $sql);
while ($row = (odbc_fetch_array($exec))) {
    $insertid = $mysqldb->insert('uploadfile', [
            'aid' => str_replace('.0', '', $row['DataID']),
            'uploadtime' => strtotime($row['createdate']),
            'filepath' => $row['filepath']
        ]
    );
}
exit;
//后台表
$sql = 'select * from admin';
$exec = odbc_exec($mssqldb, $sql);
while ($row = (odbc_fetch_array($exec))) {
    $insertid = $mysqldb->insert('admin', [
            'id' => str_replace('.0', '', $row['id']),
            'username' => mb_convert_encoding($row['Uname'], "UTF-8", "GBK"),
            'password' => '7e630e446405dbe7c9a3bf8550c9dd2e',
            'createdate' => strtotime($row['CreateDate']),
            'classid' => '',
            'permissions' => '',
            'logindatetime' => strtotime($row['LoginDateTime']),
            'leavedatetime' => '',
            'orderid' => 0,
            'userid' => str_replace('.0', '', $row['userid']),
            'updatedate' => strtotime($row['updatedate']),
            'integral' => 0,
            'sex' => $row['Sex'] == 'True' ? 1 : 2,
            'question' => '',
            'answer' => '',
            'email' => '',
            'attribute' => $row['attribute'],
            'image' => '',
            'pddir' => '',
            'name' => '',
            'tel' => '',
            'mobile' => ''
        ]
    );
}
$sql = 'select * from class';
$exec = odbc_exec($mssqldb, $sql);
//栏目表
while ($row = (odbc_fetch_array($exec))) {
    $data = [
        'catid' => str_replace('.0', '', $row['id']),
        'catname' => mb_convert_encoding($row['classname'], "UTF-8", "GBK"),
        'parentid' => intval($row['classid']),
        'description' => mb_convert_encoding($row['info'], "UTF-8", "GBK"),
        'orderid' => intval($row['OrderID']),
        'showtype' => intval($row['showtype']),
        'icon' => $row['maxico'],
        'en' => '',
        'keyword' => mb_convert_encoding($row['KeyWord'], "UTF-8", "GBK"),
        'catpath' => $row['DirName'],
    ];
    $insertid = $mysqldb->insert('category', $data);
    echo 'category' . $row['id'] . $row['classname'] . 'success' . PHP_EOL;
}

//点击表
$sql = 'select * from InfoClick';
$exec = odbc_exec($mssqldb, $sql);
while ($row = (odbc_fetch_array($exec))) {
    $insertid = $mysqldb->insert('hits', [
            'aid' => str_replace('.0', '', $row['DataID']),
            'views' => intval($row['ClickCount']),
            'catid' => str_replace('.0', '', $row['ClassID']),
        ]
    );
}

function haddle(&$data)
{
    $data['content'] = addslashes(stripslashes($data['content']));
    $data['WriteTime'] = strtotime($data['WriteTime']);
}