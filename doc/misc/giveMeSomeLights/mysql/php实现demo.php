@link http://javalifuqing.blog.163.com/blog/static/8369903520124295232311/



mysql 分布式事务（xa）

2012-05-29 17:02:32|  分类： mysql |字号 订阅
这是写的一个测试分布式事务的脚本，参考了网上的一个脚本，但是那个在我这里脚本不能正常执行，就自己改了一下

<?php

$mapfarm = new mysqli("10.128.51.121", "public", "public", "db1")or die("$mapfarm ： 连接失败");
$map = new mysqli("10.128.51.121", "public", "public", "db2")or die("$map ： 连接失败");

$grid = uniqid("");
$map->query("XA START '$grid'"); //准备事务1
$mapfarm->query("XA START '$grid'"); //准备事务2


try {
    $return = $map->query("UPDATE test_transation2 SET name='张宏' WHERE id=2"); //第一个分支事务准备做的事情，通常他们会记录进日志
    if ($return == false) {
        throw new Exception("<a href='http://'>113更新失败!</a>");
    }
    $return = $mapfarm->query("UPDATE test_transation1 SET name='胡代荣' WHERE id=1"); //第二个分支事务准备做的事情，通常他们会记录进日志
    if ($return == false) {
        throw new Exception("116更新失败!");
    }

    $map->query("XA END '$grid'");
    $map->query("XA PREPARE '$grid'");

    $mapfarm->query("XA END '$grid'");
    $mapfarm->query("XA PREPARE '$grid'"); //通知是否准备提交

    $mapfarm->query("XA COMMIT '$grid'"); //这两个基本同时执行
    $map->query("XA COMMIT '$grid'");
} catch (Exception $e) {
    $mapfarm->query("XA ROLLBACK '$grid'");
    $map->query("XA ROLLBACK '$grid'");
    print $e->getMessage();
}

$map->query("XA START '$grid'");

$sql = "SELECT * FROM test_transation2 WHERE id=2";
$result = $map->query($sql) or die("查询失败");
echo "<pre>";
print_r(mysqli_fetch_assoc($result));
echo "</pre>";

$map->query("XA END '$grid'");
$map->query("XA PREPARE '$grid'");

$map->query("XA COMMIT '$grid'");


$mapfarm->query("XA start $grid");
$sql = "insert into test_transation1 values(4,'小虎')";

$result = $mapfarm->query($sql);
$sql = "select * from test_transation1";
$result = $mapfarm->query($sql) or die("查询失败");
echo "<pre>";
print_r(mysqli_fetch_assoc($result));
echo "</pre>";
$mapfarm->query("XA END $grid");
$mapfarm->query("XA prepare $grid");
$mapfarm->query("XA commit $grid");

$map->close();
$mapfarm->close();
?>



输出 如下

Array (     [id] => 2     [name] => 张宏 )
Array (     [id] => 1     [name] => 胡代荣 ) $grid 的值是取得当前的时间的一个整数值，在用分布式事务之前需要启动
innodb_support_xa 以支持分布式事务

mysql> show variables lik
+-------------------+----
| Variable_name     | Val
+-------------------+----
| innodb_support_xa | ON
+-------------------+----
1 row in set (0.00 sec)
上面例子中其实是启动的一个事务，但是看起来有点像多个，我们姑且把他们称为组建。
要执行一个全局事务，必须知道涉及到了哪些组件，并且把每个组件引到一点，在此时，组件可以被提交或回滚时。根据每个组件报告的有关组件效能的内容，这些组件必须作为一个原子性群组全部提交或 回滚。即，要么所有的组件必须提交，要么所有的组件必须回滚。要管理一个全局事务，必须要考虑任何组件或连接网络可能会故障。

用于执行全局事务的过程使用两阶段提交（2PC），发生时间在由全局事务的分支进行的行动已经被执行之后。

1. 在第一阶段，所有的分支被预备好。即，它们被TM告知要准备提交。通常，这意味着用于管理分支的每个RM会记录对于被稳定保存的分支的行动。分支指示是否它们可以这么做。这些结果被用于第二阶段。

2. 在第二阶段，TM告知RMs是否要提交或 回滚。如果在预备分支时，所有的分支指示它们将能够提交，则所有的分支被告知要提交。如果在预备时，有任何分支指示它将不能提交，则所有分支被告知 回滚。

分布式事务也有他的局限，它对网络贷款要求比较高，如果所有的mysql 数据库在同一个服务器那么久没有什么影响，但是如果分布在不同的局域网，那么可能经常因为网络延迟导致事务失败。

上面例子中其实是启动的一个事务





下边是自己写的。

<?php

/**
 *XA MYSQL
 **/

//连接数据库


//$grid = uniqid("");

//echo $grid;exit;


function getConnect()
{

    $con = mysql_connect("localhost", "root", "root") or die("连接数据库失败1");

    mysql_select_db("mysql", $con) or die("找不到数据源1");

    mysql_query("set names gb2312");


}

//连接数据库1

function getConnect1()
{

    $con = mysql_connect("localhost", "root", "root") or die("连接数据库失败1");

    mysql_select_db("vipstore", $con) or die("找不到数据源2");

    mysql_query("set names gb2312");


}


$sql = "INSERT INTO `mysql`.`help_category` (

`help_category_id` ,

`name` ,

`parent_category_ids` ,

`url`

)

VALUES (

'38', 'lfq', '1', '/uuuuullllll'

);

";


$sql2 = "INSERT INTO `vipstore`.`test` (

`id` ,

`val`

)

VALUES (

NULL , 'lfq'

);";


function ReadyTransaction1()
{

    getConnect();

    $result = mysql_query("XA START '$grid'"); //准备事务1

    return $result;

}


function ReadyTransaction2()
{

    getConnect1();

    $result = mysql_query("XA START '$grid'"); //准备事务2

    return $result;

}


function getQuery1($sql)
{

    getConnect();

    $result = mysql_query($sql);

    return $result;

}


function getQuery2($sql)
{

    getConnect1();

    $r = mysql_query($sql);

    return $r;

}


$grid = uniqid("");

getQuery1("XA START '$grid'"); //准备事务1

getQuery2("XA START '$grid'"); //准备事务2


try {

    $r = getQuery1($sql);

    if ($r == false) {

        throw new Exception("数据1执行失败");

    }


    $r = getQuery2($sql2);

    if ($r == false) {

        throw new Exception("数据2执行失败");

    }

    getQuery1("XA END '$grid'");

    getQuery1("XA PREPARE '$grid'");


    getQuery2("XA END '$grid'");

    getQuery2("XA PREPARE '$grid'"); //通知是否准备提交


    getQuery2("XA COMMIT '$grid'"); //这两个基本同时执行

    getQuery1("XA COMMIT '$grid'");


} catch (Exception $e) {

    getQuery1("XA ROLLBACK '$grid'");

    getQuery2("XA ROLLBACK '$grid'");

    print $e->getMessage();


}



?>