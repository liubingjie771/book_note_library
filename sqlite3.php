
<?php
/****/
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);                    //打印出所有的 错误信息
/****/
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); //将出错信息输出到一个文本文件

//定义数据表文件位置

class DataBase extends SQLite3
{
    function __construct($dbf)
    {
		try
		{
			$this->open(dirname(__FILE__)."/".$dbf);
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}
    }
}
/**
$db=new DataBase("db/user.db");
$db->exec("drop table lg_user");
$db->exec("create table lg_user(u_id varchar(20) unique,u_name varchar(100) not null ,u_pass varchar(100) not null,u_tel varchar(12),u_qq varchar(20),u_mail varchar(100),u_info varchar(1000),u_ctime datetime not null,u_cuid varchar(20) not null,u_uptime datetime,u_upid varchar(20),u_bak varchar(1000))");
$db->exec("insert into lg_user values('20181127000001','super','".sha1("dell-8888")."','18562221224','351188949','liubingjie771@live.cn','','".date("Y-m-d H:i:s")."','20181127000001',null,null,null)");
$db->exec("insert into lg_user values('20181127201201','admin','".sha1("123456")."',null,null,null,'','".date("Y-m-d H:i:s")."','20181127000001',null,null,null)");
$dbs=$dba->query("select * from lg_user where u_name like 'admin' ");
**/

$GLOBALS["db1"]=new DataBase("data/book.db");
?>
