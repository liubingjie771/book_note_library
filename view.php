<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta version="<?php include("../version.ini"); ?>" />
<title>阅览室-图书台</title>
<style type="text/css">
body { margin:0px; padding:0px; width:800px; height:600px; }
#mail_file
{
	width:1024px;

}
#file_info
{
	width:300px;
	color:gray;
	background:lightgray;
}
#file_info th
{
	border:1px solid gray;
	cellpadding:0px;
	cellspacing:0px;
	padding:0px;
	margin:0px;
	text-align:left;
}
#file_view
{
	width:700px;
	overflow:none;
	height:768px;
}
.bkinf
{
	float:right;
	text-align:right;
	color:black;
	line-height:30px;
	margin:5px;
	padding:5px;
}
#notediv
{
	position:fixed;
	bottom:5px;
	left:5px;
	z-index:99;
}
</style>

</head>
<body>
<center>
<table id="main_file" name="main_file"><tr><td valign="top">
<table id="file_info" name="file_info" >
<?php
//判断是否为登录的状态
if($_COOKIE["book_lyclub_uid"]==""||$_COOKIE["book_lyclub_uacc"]==""||$_COOKIE["book_lyclub_name"]==""||$_COOKIE["book_lyclub_utel"]==""||$_COOKIE["book_lyclub_umail"]==""||$_COOKIE["book_lyclub_group"]=="")
{
	echo "<script>alert(\"用户未登录，请重新登陆!\");location.href='user.php?guest=yes&webpage=".urlencode("view.php?".$_SERVER["QUERY_STRING"])."';</script>";
	exit();
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
$bookid="20190416";
if($_GET["book_id"]!="")
	$bookid=$_GET["book_id"];

include("sqlite3.php");
$bk1=$GLOBALS["db1"]->query("select * from book_info where book_id=".$bookid." and 0<1");
$book1=$bk1->fetchArray();
echo "<!--";
print_r($book1);
echo "-->";
/*++++++++++bk_type_info++++++++++图书分类信息数据表++++++++++++++++++++++++++++++++++++++
  + bkt_id   + bkt_title      +  bkt_info    +  bkt_cuser +  bkt_ctime   +  bkt_bakinfo  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 分类ID号 + 分类标题或简介 + 分类详细信息 + 分类发布者 + 分类发布时间 + 分类备注信息  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
$bt1=$GLOBALS["db1"]->query("select * from bk_type_info where bkt_id=".$book1["book_type"]);
$bt2=$bt1->fetchArray();
echo "<tr height=\"40px\"><th>书名:<br/><span class=\"bkinf\">".urldecode($book1["book_name"])."</span></th></tr>";
echo "<tr><th>简介：<br/><textarea style=\"width:400px;height:100px;background:lightgray;border:0px;max-height:200px;resize:none;\">".urldecode($book1["book_info"])."</textarea></th></tr>";
echo "<tr height=\"40px\"><th>发布时间：<br/><span class=\"bkinf\">".$book1["book_ctime"]."</span></th></tr>";
echo "<tr height=\"40px\"><th>发布作者：<br/><span class=\"bkinf\">".$book1["book_cuser"]."</span></th></tr>";
echo "<tr height=\"40px\"><th>图书类型：<br/><span class=\"bkinf\">".urldecode($bt2["bkt_title"])."</span></th></tr>";
//echo "<tr height=\"40px\"><th>图书类型：<br/><span class=\"bkinf\">".urldecode($book1["book_type"])."</span></th></tr>";
?>
</table></td>
<td id="file_view" name="file_view">
<?php
if($book1["file_laster"]=="pdf")
	echo "<iframe height='100%' width='100%' src=\"pdfjs/web/viewer.html?file=".$book1["book_url"]."\"></iframe>";
elseif($book1["file_laster"]=="doc"||$book1["file_laster"]=="docx"||$book1["file_laster"]=="xls"||$book1["file_laster"]=="xlsx"||$book1["file_laster"]=="ppt"||$book1["file_laster"]=="pptx")
	echo "<iframe height='100%' width='100%' src=\"https://api.idocv.com/view/url?url=".$book1["book_url"]."\"></iframe>";

if($GLOBALS["db1"]->exec("update book_info set book_browser=book_browser+1 where book_id=".$book1["book_id"]))
{
	echo "<script>alert('请敬请阅读吧！\n谢谢大家的支持!');</script>";
}
else
{
	echo "<script>alert('此页面出现了问题，请重试！');close();</script>";
}
?>
</td></tr></table>
<form id="notediv" name="notediv" method="post" action="notebook.php?fun=save">
<?php
echo "<input type='hidden' name='book_id' id='book_id' value='".$bookid."' />";
echo "<input type='hidden' name='user_id' id='user_id' value='".$_COOKIE["book_lyclub_uid"]."' />";
?>
<p align="center">
	<font style="font-weight:bold;font-size:24px;float:left;">读书笔记：</font>
	<select id="note_pub" name="note_pub"><option value='1'>公开笔记</option><option value='0'>私有笔记</option></select>
	<input type="submit" value="保存笔记" style="float:right;background:orange;color:blue;font-weight:bold;font-size:18px;margin:3px;padding:3px;"/>
</p>
<textarea id="notebook" name="notebook"></textarea>
</form>
<script language="javascript">
var bdw=document.documentElement.clientWidth;
var bdh=document.documentElement.clientHeight;
bdh=bdh-15;
bdw=bdw-15;
fvw=bdw-400;
document.getElementById("main_file").style.height=bdh+"px";
document.getElementById("main_file").style.width=bdw+"px";
document.getElementById("file_view").style.height=bdh+"px";
document.getElementById("file_view").style.width=fvw+"px";
ntw=document.getElementById("file_info").offsetWidth;
ntw=ntw-10;
document.getElementById("notebook").style.width=ntw+"px";
document.getElementById("notediv").style.width=ntw+"px";
var nth=document.getElementById("file_info").offsetHeight;
nth=bdh-nth-60;
document.getElementById("notebook").style.height=nth+"px";
</script>
</center>
</body>
</html>
