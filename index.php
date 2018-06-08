<?php
/**
 * Created by PhpStorm.
 * User: weibo
 * Date: 2018/6/8
 * Time: 下午12:06
 */

include_once 'database.php';

$db = new DB('127.0.0.1','mysql_basic','root','****');


//echo $db->add('jr_rubbish',['fistname' => 'jj', 'lastname' => 'linjunjie', 'email' => 'jj@163.com']);

//echo $db->delete('jr_rubbish',['lastname' => ['like', '%hui%'], 'id' => ['>', 102]]);

//$db->update('jr_rubbish',['fistname' => 'TATA','lastname'=>'HH'],['id' => ['=',139]]);

$db->search('','jr_rubbish',['id' => ['=', 139]]);

//$db->checkWhere([
//    'name' => ['=','Tom'],
//    'age' => ['=', 18],
//    'hobby' => ['like','%_篮球']
//]);