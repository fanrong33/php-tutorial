<?php
/**
 * @version 1.0.0 build 20180301
 * TODO
 * [mongo-查询（2）——比较/$in/$nin/$or/$not](https://www.cnblogs.com/yuechaotian/archive/2013/02/04/2891506.html)
 */
header('Content-Type:text/html; charset=utf-8');

// 连接mongodb
// $mongo = new MongoClient('mongodb://[username:password@]localhost:27017'); // 连接默认主机和端口为：mongodb://localhost:27017
$mongo = new MongoClient('mongodb://localhost:27017');

$db = $mongo->test; // 获取名称为 "test" 的数据库 public MongoCollection __get ( string $name )




// 查询数据
// 查询 like
$db->users->find(array("name" => new MongoRegex("/Joe/")));
// 相当于 SELECT * FROM users WHERE name LIKE "%Joe%"

$db->users->find(array("name" => new MongoRegex("/^Joe/")));
// 相当于 SELECT * FROM users WHERE name LIKE "Joe%"



// 条件操作符 > $gt, >= $get, < $lt, <= $lte, != $ne
// Find where age != 20
$user = $db->users->findOne(array('age' => array('$ne' => 20)));

// Find where age > 20
$user = $db->users->findOne(array('age' => array('$gt' => 20)));

// Find where age >= 20
$user = $db->users->findOne(array('age' => array('$gte' => 20)));

// Find where age < 20
$user = $db->users->findOne(array('age' => array('$lt' => 20)));

// Find where age < 20
$user = $db->users->findOne(array('age' => array('$lte' => 20)));


// Find where age=20 or age=21
$where = array('$or' => array( array('age'=>20), array('age'=>21) ));
$cursor = $db->users->find($where);

// Find where name='杜甫' AND age=21，注意：这里是 '$and'=> array(array(), array()), 不是 '$and'=> array(?, ?), 很容易没注意
$where = array('$and' => array( array('name'=>'杜甫'), array('age'=>21) ));
$cursor = $db->users->find($where);
foreach ($cursor as $document) {
    // var_dump($document);
}


// $in & $nin
$db->users->find(array('age'=>array('$in'=>array(20,21))));


