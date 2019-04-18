<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta version="<?php include("../version.ini"); ?>" />
<title>图书管理页面</title>
<style type="text/css">
body
{
	margin:0px;
	padding:0px;
	background:lightyellow;
	cursor:default;
}
</style>
</head>
<body>
<center>
<?php
//判断是否为登录的状态
if($_COOKIE["book_lyclub_uid"]==""||$_COOKIE["book_lyclub_uacc"]==""||$_COOKIE["book_lyclub_name"]==""||$_COOKIE["book_lyclub_utel"]==""||$_COOKIE["book_lyclub_umail"]==""||$_COOKIE["book_lyclub_group"]=="")
{
	echo "<script>alert(\"用户未登录，请重新登陆!\");location.href='/user.php?webpage=portal/index.php';</script>";
	exit();
}
if($_COOKIE["book_lyclub_group"]=="admin"||$_COOKIE["book_lyclub_group"]=="super")
{
	echo "";
}
else
{
	echo "<script>alert(\"用户没有权限访问此网页!\");location.href='../index.php';</script>";
	exit();
}
include("../sqlite3.php");

//获取用户显示名
function usr_alias($str)
{
	if($str=="admin")
	{
		return "<span title='".$str."'>管理员</span>";
	}
}

//查询图书分类表获取类型显示名
/*++++++++++bk_type_info++++++++++图书分类信息数据表++++++++++++++++++++++++++++++++++++++
  + bkt_id   + bkt_title      +  bkt_info    +  bkt_cuser +  bkt_ctime   +  bkt_bakinfo  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 分类ID号 + 分类标题或简介 + 分类详细信息 + 分类发布者 + 分类发布时间 + 分类备注信息  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
function book_type($str)
{
	$bt1=$GLOBALS["db1"]->query("select bkt_title from bk_type_info where bkt_id=".$str);
	$bt2=$bt1->fetchArray();
	return urldecode($bt2["bkt_title"]);
}
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
echo "<table border='0' cellpadding='0px' cellspacing='5px' width='80%'>";
$bk1=$GLOBALS["db1"]->query("select * from book_info");
while($bk2=$bk1->fetchArray())
{
	echo "<tr><td>";
	echo "<!--";
	print_r($bk2);
	echo "-->";
	echo "<table border='1' cellpadding='0' cellspacing='0' width='100%'>";
	echo "<tr>";
	echo "<th>图书ID书号：</th><td><a href='/view.php?book_id=".$bk2["book_id"]."' target='_blank'>".$bk2["book_id"]."</a></td><th>图书书名：</th><td colspan='3'>".urldecode($bk2["book_name"])."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<th>图书分组类型：</th><td>".book_type($bk2["book_type"])."</td><th>图书发布者：</th><td>".usr_alias($bk2["book_cuser"])."</td><th>图书发布日期：</th><td>".urldecode($bk2["book_ctime"])."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<th>图书详细信息：</th><td colspan='5'>".urldecode($bk2["book_info"])."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<th>图书格式：</th><td>".$bk2["file_laster"]."</td><th>浏览数：</th><td>".$bk2["book_browser"]."</td><th>统计数：</th><td>".urldecode($bk2["book_downer"])."</td>";
	echo "</tr>";
	echo "</table>";
	echo "</td></tr>";
}
echo "</table>";
if($_COOKIE["book_lyclub_group"]=="admin"||$_COOKIE["book_lyclub_group"]=="super"||$_COOKIE["book_lyclub_group"]=="auth")
{
	echo "<p><a href=\"create.php?run=USR782296B679017DD66B2291B803DCE2CD7C9D089D10001\">上传图书并填写图书信息</a></p>";
}
?>

</center>
</body>
</html>