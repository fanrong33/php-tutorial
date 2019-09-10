<?php
/**
 * @version 1.0.6 build 20180223
 */
header('Content-Type:text/html; charset=utf-8');

// 在php中使用mongodb你必须使用 mongodb 的 php驱动。
// * [MongoDB PHP](http://www.runoob.com/mongodb/mongodb-php.html)
// * [MongoClient 类](http://php.net/manual/zh/class.mongoclient.php)
// * [SQL 到 Mongo 的对应表](http://php.net/manual/zh/mongo.sqltomongo.php)

// 1、连接mongodb
// $mongo = new MongoClient('mongodb://[username:password@]localhost:27017'); // 连接默认主机和端口为：mongodb://localhost:27017
$mongo = new MongoClient('mongodb://localhost:27017');
$dbs = $mongo->listDBs();
print_r($dbs);


// 2、切换数据库
$db = $mongo->test; // 获取名称为 "test" 的数据库 public MongoCollection __get ( string $name )


// 3、插入数据
$collection = $db->users; // 选择集合
$response = $collection->insert(array("name" => "zhangsan", "age" => 18));
$response = $collection->insert(array("name" => "xiaobai", "age" => 20));
// dump($response);
/*
array(4) {
  ["n"] => int(0)
  ["ok"] => float(1)
  ["err"] => NULL
  ["errmsg"] => NULL
}*/




// 4、查询数据
$cursor = $db->users->find();
if($cursor->count()){
    // 迭代显示文档标题
    foreach ($cursor as $document) {
        // dump($document);
    }
}
// This returns a MongoCursor. Often, when people are starting out, they are more comfortable using an array. 
// To turn a cursor into an array, use the iterator_to_array() function.
$array = iterator_to_array($cursor);


$cursor = $db->users->find(array('name'=>'zhangsan'));
foreach ($cursor as $document) {
    // dump($document);
}


// 按age字段升序排序。1为升序，-1为降序。
// $cursor = $db->find()->sort(array('age' => 1));
// skip() limit()


$user = $db->users->findOne(array('name'=>'zhangsan'), array('age'=>false));




// 5、更新数据
$where = array('name' => '杜甫');
$update = array('$set' => array('age' => 25)); # 千万注意：这里是有$set的！！！
$response = $db->users->update($where, $update, array('upsert'=>true, 'multiple'=>false));
// 相当于 UPDATE users **SET** a=1 WHERE b='q'
// dump($response);
/*
array(6) {
  ["ok"] => float(1)
  ["n"] => int(1)
  ["nModified"] => int(1)
  ["err"] => NULL
  ["errmsg"] => NULL
  ["updatedExisting"] => bool(true)
}*/
$cursor = $db->users->find(array('name'=>'杜甫'));
foreach ($cursor as $document) {
    dump($document);
}



// 6、删除数据
$response = $db->users->remove(array('name'=>'李白'), array('justOne'=>true));
// dump($response);
/*
array(4) {
  ["n"] => int(1)
  ["ok"] => float(1)
  ["err"] => NULL
  ["errmsg"] => NULL
}*/





