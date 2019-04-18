<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta version="<?php include("../version.ini"); ?>" />
<title>用户笔记页面</title>
<style type="text/css">
body
{
	margin:0px;
	padding:0px;
	background:lightyellow;
}
#notebook_list
{
	position:fixed;
	top:0px;
	left:0px;
	right:0px;
	height:50%;
	overflow-y:auto;
	overflow-x:none;
	border: solid 2px yellow;
}
#ntview
{
	position:fixed;
	bottom:10px;
	left:10%;
	right:10%;
	height:45%;
	width:80%;
	overflow-y:auto;
	overflow-x:none;
	border:solid 1px yellow;
}
</style>
</head>
<body>
<center>
<?php
//判断是否为登录的状态
if($_GET["fun"]!="save")
{
	echo "";
}
elseif($_COOKIE["book_lyclub_uid"]==""||$_COOKIE["book_lyclub_uacc"]==""||$_COOKIE["book_lyclub_name"]==""||$_COOKIE["book_lyclub_utel"]==""||$_COOKIE["book_lyclub_umail"]==""||$_COOKIE["book_lyclub_group"]=="")
{
	echo "<script>alert(\"用户未登录，请重新登陆!\");location.href='user.php?guest=yes&webpage=".urlencode("notebook.php?".$_SERVER["QUERY_STRING"])."';</script>";
	exit();
}

include("sqlite3.php");

if($_GET["fun"]=="save")
{
	if($_POST["notebook"]=="")
	{
		echo "<script>alert(\"笔记无法保存，因为没有写笔记!\");history.go(-1);</script>";
		exit();
	}
	if($_POST["book_id"]=="")
	{
		echo "<script>alert(\"笔记无法保存1，请返回重新复制并保存!\");history.go(-1);</script>";
		exit();
	}
	if($_POST["user_id"]=="")
	{
		echo "<script>alert(\"笔记无法保存2，请返回重新复制并保存!\");history.go(-1);</script>";
		exit();
	}
	$bookid=$_POST["book_id"];
	
	/*+++++++++++++++bk_note_info+++++++用户的读书笔记信息存储表++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	+     nt_id              +    book_id              +     user_id          + nt_pub   +  nt_ctime    +  nt_bakinfo    +
	--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	+ 读书笔记的ID号，文件名 +  读书笔记所在的图书ID号 + 读书笔记所登录的用户 + 是否公开 + 笔记存放时间 + 笔记备注信息   +
	--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	$nx1=$GLOBALS["db1"]->query("select max(nt_id) as ntmaxid from bk_note_info");
	$nx2=$nx1->fetchArray();
	$ntid=$nx2["ntmaxid"];
	if($ntid=="")
	{
		$ntid="2000";
	}
	$ntid=$ntid+1;
	$ntsql="insert into bk_note_info values(".$ntid.",".$_POST["book_id"].",".$_POST["user_id"].",".$_POST["note_pub"].",'".date("Y-m-d H:i:s")."','')";
	if(!$myfile = fopen("notes/".$ntid.".txt", "w"))
	{
		echo "<script>alert(\"用户的笔记写入失败!\");history.go(-1);</script>";
		exit();
	}
	$txt = $_POST["notebook"];
	fwrite($myfile, $txt);
	fclose($myfile);
	//*
	if($GLOBALS["db1"]->exec($ntsql))
	{
		echo "<script>alert(\"用户的笔记保存成功!\");location.href='index.php';</script>";
		exit();
	}
	else	
	{
		echo "<script>alert(\"用户的笔记保存失败!\");history.go(-1);</script>";
		exit();
	}
	//*/
}
elseif($_GET["fun"]=="readarr")
{
	if($_GET["book_id"]=="")
	{
		echo "<script>alert(\"bookid不能为空!\");location.href='index.php';</script>";
		exit();
	}	
	function booknameview($book)
	{
		$data2=$GLOBALS["db1"]->query("select book_id,book_name from book_info where book_id=".$book);
		$da2=$data2->fetchArray();
		return urldecode($da2["book_name"]);
	}

	function useraliasview($user)
	{
		$data3=$GLOBALS["db1"]->query("select book_uid,book_uname from bk_user_info where book_uid=".$user);
		$da3=$data3->fetchArray();
		return urldecode($da3["book_uname"]);
	}
	
	echo "<div id=\"notebook_list\" name==\"notebook_list\">";
	echo "<table border='1' cellspacing='0' cellpadding='3' >";
	echo "<tr><th>图书名</th><th>用户名</th><th>写入时间</th><th>查看</th></tr>";
	$a1=$GLOBALS["db1"]->query("select * from bk_note_info where nt_pub=1 and book_id=".$_GET["book_id"]." order by book_id asc");
	while($a2=$a1->fetchArray())
	{
		echo "<tr>";
		echo "<td align='center'>".booknameview($a2["book_id"])."</td>\n";
		echo "<td align='center'>".useraliasview($a2["user_id"])."</td>\n";
		echo "<td align='center'>".$a2["nt_ctime"]."</td>\n";
		echo "<td align='center'><a href='?fun=read&ntid=".$a2["nt_id"]."' target='ntview'>公开查看</a></td>\n";
		echo "</tr>";
	}
	$b1=$GLOBALS["db1"]->query("select * from bk_note_info where nt_pub=0 and user_id=".$_COOKIE["book_lyclub_uid"]." and book_id=".$_GET["book_id"]." order by book_id asc");
	while($b2=$b1->fetchArray())
	{
		echo "<tr>";
		echo "<td align='center'>".booknameview($b2["book_id"])."</td>\n";
		echo "<td align='center'>".useraliasview($b2["user_id"])."</td>\n";
		echo "<td align='center'>".$b2["nt_ctime"]."</td>\n";
		echo "<td align='center'><a href='?fun=read&ntid=".$b2["nt_id"]."' target='ntview'>查看私有</a></td>\n";
		echo "</tr>";
	}
	echo "</table>";
	echo "</div>";
	echo "<iframe src='' id='ntview' name='ntview'></iframe>";
}
elseif($_GET["fun"]=="read")
{
	$file_path = "notes/".$_GET["ntid"].".txt";
	if(file_exists($file_path))
	{
		$str = file_get_contents($file_path);
		$str = str_replace("\r\n","<br />",$str);
		echo $str;
	}
}
else
{
	header("Location: ?fun=readarr&book_id=".$_GET["book_id"]);
}
?>
</center>
</body>
</html>