<?php
ini_set('odbc.defaultlrl', '1024000');
require 'conf.php';
    $maxid = $mysqldb->max('news', 'id');
    $maxid == false && $maxid = 0;
//文章表
    //6744048741
    $sql = 'SELECT  CONVERT(text,Content) as Content FROM Word WHERE (ID = 6744048948) ORDER BY ID';
    echo $sql . PHP_EOL;
    $exec = odbc_exec($mssqldb, $sql);
    var_dump(odbc_error());
    while ($row = (odbc_fetch_array($exec))) {
        var_dump($row);
    }
