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
<fieldset style="border:solid 1px green;width:80%;">
<?php
$runid="USR".strtoupper(sha1("lyclub2016-891021"))."10001";
if($_GET["run"]!=$runid)
{
	echo "<script>alert(\"无权访问此网页，请重试！\");location.href='index.php';</script>";
	
}
//判断是否为登录的状态
if($_COOKIE["book_lyclub_uid"]==""||$_COOKIE["book_lyclub_uacc"]==""||$_COOKIE["book_lyclub_name"]==""||$_COOKIE["book_lyclub_utel"]==""||$_COOKIE["book_lyclub_umail"]==""||$_COOKIE["book_lyclub_group"]=="")
{
	echo "<script>alert(\"用户未登录，请重新登陆!\");location.href='/user.php?webpage=".urlencode("portal/create.php?".$_SERVER["QUERY_STRING"])."';</script>";
	exit();
}
//判断用户组是否能够创建图书
if($_COOKIE["book_lyclub_group"]=="admin"||$_COOKIE["book_lyclub_group"]=="super"||$_COOKIE["book_lyclub_group"]=="auth")
{
	echo "";
}
else
{
	echo "<script>alert(\"你没有创建图书的权限！\");location.href='index.php';</script>";
	exit();
}
include("../sqlite3.php");
if($_GET["fun"]=="one")
{
	echo "<legend><h1>创建图书信息</h1></legend>";
	echo "<form action=\"?run=".$_GET["run"]."&fun=two\" method=\"post\" enctype=\"multipart/form-data\">";
	echo <<<TABLEONE1
		<table border='1' cellspacing='0' cellpadding='5'>
		<tr>
			<th>图书书名：</th><td><input type="text" id="bk_title" name="bk_title" value="" size="40" /></td>
			<th>图书类型：</th><td><select id="bk_type" name="bk_type">
TABLEONE1;
/*++++++++++bk_type_info++++++++++图书分类信息数据表++++++++++++++++++++++++++++++++++++++
  + bkt_id   + bkt_title      +  bkt_info    +  bkt_cuser +  bkt_ctime   +  bkt_bakinfo  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 分类ID号 + 分类标题或简介 + 分类详细信息 + 分类发布者 + 分类发布时间 + 分类备注信息  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
					$bt1=$GLOBALS["db1"]->query("select bkt_id,bkt_title from bk_type_info");
					while($bt2=$bt1->fetchArray())
					{
						echo "<option value='".$bt2["bkt_id"]."'>".urldecode($bt2["bkt_title"])."</option>\n";
					}
	echo <<<TABLEONE2
			</select></td>
		</tr>
		<tr>
			<th>图书详细信息：</th><td colspan="3"><textarea id="bk_xinf" name="bk_xinf" cols="100" rows="10"></textarea></td>
		</tr>
		<tr>
			<th>选择图书文件：</th><td><input type="file" id="bk_file" name="bk_file"/></td>
			<td colspan="2" style="color:red;">允许上传任意大小的PDF文件<br/>允许上传小于1Mb的DOC、DOCX、XLS、XLSX、PPT、PPTX类型的文件</td>
		</tr>
		</table>
		<p><input type="submit" value="验证图书信息和图书文件" style="background:yellow;color:blue;font-weight:bold;font-size:16px;" /></p>
TABLEONE2;
	echo "</form>";
}
elseif($_GET["fun"]=="two")
{
	//判断所有信息是否为非空
	if($_POST["bk_title"]==""||$_POST["bk_type"]==""||$_POST["bk_xinf"]=="")
	{
		echo "<script>alert(\"信息没有完全填写完！\");history.go(-1);</script>";
		exit();
	}
	$booktit=$_POST["bk_title"]; 		//图书book_name
	$booktype=$_POST["bk_type"];		//图书book_type
	$bookinf=$_POST["bk_xinf"];			//图书book_info
	//判断用户的cookie是否还存在
	if($_COOKIE["book_lyclub_uid"]!=""&&$_COOKIE["book_lyclub_uacc"]!="")
	{
		$bookcuser=$_COOKIE["book_lyclub_uacc"];		//图书book_cuser
		$bookctime=date("Y-m-d H:i:s");					//图书book_ctime
		$bookread=0;
		$bookdown=0;
		$bookbakf="";
		$filename=date("YmdHis");	
		$bookurl="http://book.ly2016.club:10080/files/".date("Y")."/".date("m")."/".$filename;
	}
	//判断上传文件格式是否为pdf，否则判断是否为doc|docx|xls|xlsx|ppt|pptx的格式并判断大小不能大于1Mb的
	$farr=explode(".",$_FILES["bk_file"]["name"]);
	$bflaster=$farr[(count($farr)-1)];
	if($_FILES["bk_file"]["name"]=="")
	{
		echo "<script>alert(\"没有上传文件，请重试！\");history.go(-1);</script>";
		exit();
	}
	elseif($_FILES["bk_file"]["error"]>0)
	{
		echo "<script>alert(\"上传文件错误".$_FILES["bk_file"]["error"]."\");history.go(-1);</script>";
		exit();
	}
	elseif($bflaster=="pdf")
	{
		$bookurl=$bookurl.".".$bflaster;
	}
	elseif($bflaster=="docx"||$bflaster=="doc"||$bflaster=="xls"||$bflaster=="xlsx"||$bflaster=="ppt"||$bflaster=="pptx")
	{
		if($_FILES["bk_file"]["size"]<(1024*1024))
		{
			$bookurl=$bookurl.".".$bflaster;
		}
		else
		{
			unlink($_FILES["bk_file"]["tmp_name"]);
			echo "<script>alert(\"非PDF文件不能大于1Mb！\");history.go(-1);</script>";
			exit();
		}
	}
	$filesave="../files/".date("Y")."/".date("m")."/".$filename.".".$bflaster;
	if(!move_uploaded_file($_FILES["bk_file"]["tmp_name"],$filesave))
	{
		unlink($_FILES["bk_file"]["tmp_name"]);
		echo "<script>alert(\"移动文件错误！\");history.go(-1);</script>";
	}
	//查询图书ID号并自加一
	$a1=$GLOBALS["db1"]->query("select max(book_id) as maxid from book_info");
	$a2=$a1->fetchArray();
	$bookcid=$a2["maxid"];
	$bookcid=$bookcid+1;
	//组合sql语句并执行语句
	$isql="insert into book_info values(".$bookcid.",'".urlencode($booktit)."',".$booktype.",'".$bflaster."','".$bookurl."','".urlencode($bookinf)."',".$bookread.",".$bookdown.",'".$bookcuser."','".$bookctime."','".urlencode($bookbakf)."')";
	if($GLOBALS["db1"]->exec($isql))
	{
		echo "<script>alert(\"文件上传成功！\\n图书信息添加成功！\");location.href=\"index.php\";</script>";
	}
	else
	{
		unlink($filesave);
		echo "<script>alert(\"图书信息添加失败\");history.go(-1);</script>";
	}
}
elseif($_GET["fun"]=="")
{
	header("Location: ?run=".$_GET["run"]."&fun=one");
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

/*+++++++++++bk_log_info++++++++++图书操作日志数据表+++++++++++++++++++++++++++++++++++++++++++++++++++++
  +  bk_id   +  usr_id  +            usr_op_info      +     op_number       + op_ctime  +  op_bakinfo   +
--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 图书ID号 + 用户ID号 + 操作类型（下载或浏览或上传）+ 记录操作时统计数加1 + 操作时间  +  操作备注信息 +
--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
?>
</fieldset>
</center>
</body>
</html>