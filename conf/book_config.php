<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta version="<?php include("../version.ini"); ?>" />
<title>图书数据表的初始化</title>
<style type="text/css">
suc
{
	background:lightgreen;
	color:blue;
	font-weight:bold;
	padding:3px;
}
fail
{
	background:red;
	color:yellow;
	font-weight:bold;
	padding:3px;
}
</style>
</head>
<body>
<?php
//*
if($_COOKIE["book_lyclub_group"]=="super" || $_COOKIE["book_lyclub_group"]=="admin")
{
	echo "";
}
else
{
	echo "<script>alert(\"无权访问此网页，请重试！\");location.href='/index.php';</script>";
}
//*/
include("../sqlite3.php");
//$GLOBALS["db1"]=new database("data/book.db");
/*++++++++++++++++book_info++++++图书信息数据表+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + book_id  +    book_name   + book_type + file_laster +  book_url +  book_info   + book_browser   + book_downer    +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 图书ID号 + 图书标题或简介 + 图书分类  + 文件后缀名  + 图书地址  + 图书详细信息 + 图书浏览统计数 + 图书下载统计数 +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
--+++++++++++++++++++++++++++++++++++++++++++++
  + book_cuser +  book_ctime  +  book_bakinfo +
--+++++++++++++++++++++++++++++++++++++++++++++
  + 图书发布者 + 图书发布时间 + 图书备注信息  +
--+++++++++++++++++++++++++++++++++++++++++++++*/
$dsql[]="drop table book_info";
$csql[]="create table book_info(book_id long unique,book_name varchar(200) not null,book_type int ,file_laster varchar(100) not null,book_url varchar(1000) not null,book_info varchar(5000),book_browser bigint not null,book_downer bigint not null,book_cuser varchar(100) not null,book_ctime datetime not null,book_bakinfo varchar(5000) )";

$isql[]="insert into book_info values(2019040001,'".urlencode("《读者》原创版
2019年第2期")."',1,'pdf','".urlencode("/files/2019/04/DuZhe201902.pdf")."','".urlencode("《读者》2019年第2期，从互联网中下载的扫描版。请大家尽情的浏览吧！")."',0,0,'admin','2019-04-16 16:54:30','')";
$isql[]="insert into book_info values(2019040002,'".urlencode("三级公共英语183真题详解")."',2,'pdf','".urlencode("/files/2019/04/20190416202300.pdf")."','".urlencode("三级公共英语183真题详解+标准预测2018年6月(3套)试卷")."',0,0,'admin','2019-04-16 20:24:30','')";

/*++++++++++bk_type_info++++++++++图书分类信息数据表++++++++++++++++++++++++++++++++++++++
  + bkt_id   + bkt_title      +  bkt_info    +  bkt_cuser +  bkt_ctime   +  bkt_bakinfo  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 分类ID号 + 分类标题或简介 + 分类详细信息 + 分类发布者 + 分类发布时间 + 分类备注信息  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
$dsql[]="drop table bk_type_info";
$csql[]="create table bk_type_info(bkt_id int unique,bkt_title varchar(500) not null,bkt_info varchar(1000) not null,bkt_cuser varchar(100) not null,bkt_ctime datetime,bkt_bakinfo varchar(5000) )";

$isql[]="insert into bk_type_info values(1,'".urlencode("期刊图书类")."','".urlencode("杂志期刊--图书类")."','admin','2019-04-16 12:12:12','')";
$isql[]="insert into bk_type_info values(2,'".urlencode("期刊报纸类")."','".urlencode("杂志期刊--报刊类")."','admin','2019-04-16 12:13:12','')";

/*+++++++++++bk_log_info++++++++++图书操作日志数据表+++++++++++++++++++++++++++++++++++++++++++++++++++++
  +  bk_id   +  usr_id  +            usr_op_info      +     op_number       + op_ctime  +  op_bakinfo   +
--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 图书ID号 + 用户ID号 + 操作类型（下载或浏览或上传）+ 记录操作时统计数加1 + 操作时间  +  操作备注信息 +
--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
$dsql[]="drop table bk_log_info";
$csql[]="create table bk_log_info(bk_id bigint,usr_id bigint,usr_op_info varchar(5000) not null,op_number bigint,op_ctime datetime not null,op_bakinfo varchar(5000) )";

$isql[]="insert into bk_log_info values(20190416,9001,'".urlencode("浏览图书<==>http://book.ly2016.club:10080/view.php")."',1,'2019-04-16 17:00:02','')";

/*++++++bk_user_info+++++++++++++++++++图书用户信息数据表+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  +  book_uid   +  book_uacc   + book_uname + book_upass + book_ugroup  +  book_utelphone + book_uemail     +  book_uyzm  + book_uctime  + book_bakinfo  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 用户ID号码  +  用户账号名  +  用户别名  +  用户密码  + 用户类型组   +   用户手机号码  +   用户邮箱地址  +  用户邀请码 + 用户创建时间 +  用户备注信息 +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
$dsql[]="drop table bk_user_info";
$csql[]="create table bk_user_info(book_uid int,book_uacc varchar(100) not null,book_uname varchar(100) not null,book_upass varchar(100) not null,book_ugroup varchar(100) not null,book_utelphone varchar(20) not null,book_uemail varchar(100) not null,book_uyzm varchar(100) ,book_uctime datetime , book_bakinfo varchar(5000))";

$isql[]="insert into bk_user_info values(90001,'admin','".urlencode("管理员")."','".sha1("891021")."','super','18562221224','liubingjie771@live.cn','','2019-04-16 12:12:12','')";
$isql[]="insert into bk_user_info values(90002,'liubingjie771','".urlencode("刘星云")."','".sha1("lbj*891021")."','admin','18562221224','liubingjie771@live.cn','','2019-04-16 12:12:12','')";

/*+++++++++++++++bk_note_info+++++++用户的读书笔记信息存储表++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  +     nt_id              +    book_id              +     user_id          + nt_pub   +  nt_ctime    +  nt_bakinfo    +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 读书笔记的ID号，文件名 +  读书笔记所在的图书ID号 + 读书笔记所登录的用户 + 是否公开 + 笔记存放时间 + 笔记备注信息   +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
$dsql[]="drop table bk_note_info";
$csql[]="create table bk_note_info(nt_id int unique,book_id long not null,user_id int not null,nt_pub int not null,nt_ctime datetime not null,nt_bakinfo varchar(5000))";

//删除数据表的循环体
for($h=0;$h<count($dsql);$h++)
{
	echo "<p>删除结构：";
	echo $dsql[$h];
	if($GLOBALS["db1"]->exec($dsql[$h]))
	{
		echo "<suc>成功</suc>";
	}
	else
	{
		echo "<fail>失败</fail>";
	}
	echo "</p>";
	echo "<hr color='lightgray'/>";
}


//重建数据表结构的循环体
for($i=0;$i<count($csql);$i++)
{
	echo "<p>创建结构：";
	echo $csql[$i];
	if($GLOBALS["db1"]->exec($csql[$i]))
	{
		echo "<suc>成功</suc>";
	}
	else
	{
		echo "<fail>失败</fail>";
	}
	echo "</p>";
	echo "<hr color='lightgray'/>";
}


//插入数据表信息的循环体
for($j=0;$j<count($isql);$j++)
{
	echo "<p>插入数据：";
	echo $isql[$j];
	if($GLOBALS["db1"]->exec($isql[$j]))
	{
		echo "<suc>成功</suc>";
	}
	else
	{
		echo "<fail>失败</fail>";
	}
	echo "</p>";
	echo "<hr color='lightgray'/>";
}

//文件操作
echo "<p>".system("rm -rf ../files/")."</p>";
echo "<p>".system("rm -rf ../notes/")."</p>";
echo "<p>".system("chmod 777 ../files/ -R")."</p>";
echo "<p>".system("chmod 777 ../notes/ -R")."</p>";
echo "<p>".system("mkdir -pv ../notes/")."</p>";
echo "<p>".system("mkdir -pv ../files/2019")."</p>";
echo "<p>".system("cp -pvrf files ../files/2019/04")."</p>";
echo "<p>".system("mkdir -pv ../files/".date("Y")."/".date("m"))."</p>";
?>
</body>
</html>