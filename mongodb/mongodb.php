<?php
header('Content-Type:text/html; charset=utf-8');

// 在php中使用mongodb你必须使用 mongodb 的 php驱动。
// * [MongoDB PHP](http://www.runoob.com/mongodb/mongodb-php.html)
// * [MongoClient 类](http://php.net/manual/zh/class.mongoclient.php)

// 连接mongodb
// $mongo = new MongoClient('mongodb://[username:password@]localhost:27017'); // 连接默认主机和端口为：mongodb://localhost:27017
$mongo = new MongoClient('mongodb://localhost:27017');
$db = $mongo->test; // 获取名称为 "test" 的数据库 public MongoCollection __get ( string $name )


// 初始化集合，清扫战场
$response = $db->users->drop();
// dump($response);
/*array(3) {
  ["ns"] => string(10) "test.users"
  ["nIndexesWas"] => int(1)
  ["ok"] => float(1)
}
*/


// 插入数据
$collection = $db->users; // 选择集合
$effect = $collection->insert(array("name" => "zhangsan", "age" => 18));
$effect = $collection->insert(array("name" => "xiaobai", "age" => 20));
// dump($effect);
/*
array(4) {
  ["n"] => int(0)
  ["ok"] => float(1)
  ["err"] => NULL
  ["errmsg"] => NULL
}*/


// 插入多条
$users = array(
    array('name'=>'李白', 'age'=>20),
    array('name'=>'杜甫', 'age'=>21),
);
$response = $db->users->batchInsert($users);
// dump($response);
/*
array(6) {
  ["n"] => int(0)
  ["ok"] => float(1)
  ["connectionId"] => int(5)
  ["syncMillis"] => int(0)
  ["writtenTo"] => NULL
  ["err"] => NULL
}*/


// 查询数据
$cursor = $db->users->find();
if($cursor->count()){
    // 迭代显示文档标题
    foreach ($cursor as $document) {
        // dump($document);
    }
}

$cursor = $db->users->find(array('name'=>'zhangsan'));
foreach ($cursor as $document) {
    // dump($document);
}

$user = $db->users->findOne(array('name'=>'zhangsan'), array('age'=>false));


// 查询 like
// $cursor = $db->users->find(array('name'=>'zhang%'));
// foreach ($cursor as $document) {
//     dump($document);
// }


// 条件操作符
// Find where age != 20
$user = $db->users->findOne(array('age' => array('$ne' => 20)));
// dump($user);

// Find where age > 20
$user = $db->users->findOne(array('age' => array('$gt' => 20)));
// dump($user);

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
    // dump($document);
}



// 更新数据
$where = array('name' => '杜甫');
$update = array('$set' => array('age' => 25));
$response = $db->users->update($where, $update, array('upsert'=>true, 'multiple'=>false));
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


// 删除数据
$response = $db->users->remove(array('name'=>'李白'), array('justOne'=>true));
// dump($response);
/*
array(4) {
  ["n"] => int(1)
  ["ok"] => float(1)
  ["err"] => NULL
  ["errmsg"] => NULL
}*/





/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var , $echo =true, $label=null, $strict=true ) {
    $label = ( $label === null ) ? '' : rtrim($label ) . ' ';
    if (! $strict) {
        if (ini_get ('html_errors')) {
            $output = print_r ($var , true);
            $output = '<pre>' . $label . htmlspecialchars($output , ENT_QUOTES) . '</pre>' ;
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var );
        $output = ob_get_clean ();
        if (!extension_loaded ('xdebug')) {
            $output = preg_replace ("/\]\=\>\n(\s+)/m" , '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output , ENT_QUOTES) . '</pre>' ;
        }
    }
    if ( $echo) {
        echo($output );
        return null ;
    }else
        return $output ;
}