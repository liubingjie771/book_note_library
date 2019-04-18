<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta version="<?php include("../version.ini"); ?>" />
<link rel="shortcut icon" href="http://lyclub.f3322.net:82/web_images/32X32.ico" />
<title>图书库</title>
<link rel="stylesheet" type="text/css" href="conf/index.css" />
<style type="text/css">
legend
{
	font-size:48px;
	color:blue;
	font-weight:bold;
	background:gray;
	padding:10px;
}
.icon
{
	width:24px;
	height:24px;
	boder:0px;
}
td
{
	text-align:center;
}
</style>
<?php
//判断是否为登录的状态
if($_COOKIE["book_lyclub_uid"]==""||$_COOKIE["book_lyclub_uacc"]==""||$_COOKIE["book_lyclub_name"]==""||$_COOKIE["book_lyclub_utel"]==""||$_COOKIE["book_lyclub_umail"]==""||$_COOKIE["book_lyclub_group"]=="")
{
	echo "<script>alert(\"用户未登录，请重新登陆!\");location.href='user.php?guest=yes&webpage=index.php';</script>";
	exit();
}
ini_set('display_errors',0);            //错误信息1
ini_set('display_startup_errors',0);    //php启动错误信息1
error_reporting(0);                    //打印出所有的 错误信息-1
include("sqlite3.php");
?>
</head>
<body>
<center>
<fieldset>
<legend>图书目录</legend>
<form method="get">
	选择分类：
	<select id="tspl" name="tspl">
		<option value='0'>全部分类</option>
<?php
	$bft=$GLOBALS["db1"]->query("select * from bk_type_info");
	while($bfarr=$bft->fetchArray())
	{
		echo "<option value='".$bfarr["bkt_id"]."'>".urldecode($bfarr["bkt_title"])."</option>";
		$typearr[]=urldecode($bfarr["bkt_title"]);
	}
?>
	</select><input type='submit' value='搜索' />
</form>
<table border='1' cellspacing='0' cellpadding='0' width="80%">
<tr><th>图书ID号</th><th>图书标题<br/>或简介</th><th width="30%">图书<br/>详细信息</th><th>图书<br/>分类</th><th>图书浏览<br/>统计数</th><th>图书下载<br/>统计数</th><th>图书<br/>发布者</th><th>图书<br/>发布时间</th><th>阅读</th><th>下载</th><th>笔记</th></tr>
<?php

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
$bkifwe="select * from book_info ";
if($_GET["tspl"]=="")
{
	echo "<meta http-equiv='refresh' content='0;url=?tspl=0' />";
}
elseif($_GET["tspl"]==0)
{
	$bkifwe=$bkifwe."";
}
elseif($_GET["tspl"]>0)
	$bkifwe=$bkifwe."where book_type=".$_GET["tspl"];

$bks=$GLOBALS["db1"]->query($bkifwe);
$bknum=0;
while($bkf=$bks->fetchArray())
{
	++$bknum;
	echo "<tr>";
	echo "<td>".$bknum."</td>";
	echo "<td>".urldecode($bkf["book_name"])."</td>";
	echo "<td style='text-align:left;text-indent:2em;'>".urldecode($bkf["book_info"])."</td>";
	echo "<td>".$typearr[($bkf["book_type"]-1)]."</td>";
	echo "<td>".urldecode($bkf["book_browser"])."</td>";
	echo "<td>".urldecode($bkf["book_downer"])."</td>";
	echo "<td>".urldecode($bkf["book_cuser"])."</td>";
	echo "<td>".urldecode($bkf["book_ctime"])."</td>";
	echo "<th><a href=\"view.php?book_id=".urldecode($bkf["book_id"])."\" target=\"_blank\"><img class='icon' src='/images/reader.jpg' /></a></th>";
	echo "<th><a href=\"down.php?book_id=".urldecode($bkf["book_id"])."\" target=\"_blank\"><img class='icon' src='/images/downer.png' /></a></th>";
	echo "<th><a href=\"notebook.php?book_id=".urldecode($bkf["book_id"])."\" target=\"_blank\"><img class='icon' src='/images/reader.jpg' /></a></th>";
	echo "</tr>";
}
?>
</table>
</fieldset>
</center>
</body>
</html>