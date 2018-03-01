<?php
/**
 * @version 1.0.1 build 20180301
 */
header('Content-Type:text/html; charset=utf-8');

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
// 打印最新生成的 _id field
$data = array('name'=>'白居易', 'age'=>23);
$response = $db->users->insert($data);
dump($data);
/*
array(3) {
  ["name"] => string(9) "白居易"
  ["age"] => int(23)
  ["_id"] => object(MongoId)#4 (1) {
    ["$id"] => string(24) "5a8fe1c43b4db970350041a7"
  }
}
*/
dump((string)$data['_id']);
// 5a8fe1c43b4db970350041a7

// 通过_id修改字段数据, new MongoId
$insert_id = (string)$data['_id'];
$where  = array('_id' => new MongoId($insert_id));
$update = array('$set' => array('age'=>25));
$response = $db->users->update($where, $update);
dump($response);


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