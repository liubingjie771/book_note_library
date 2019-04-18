<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta version="<?php include("../version.ini"); ?>" />
<title>用户登陆页面</title>
<style type="text/css">
body
{
	margin:0px;
	padding:0px;
	background:lightyellow;
}
</style>
</head>
<body>
<center>
<?php
/**
* 验证手机号是否正确
* @author honfei
* @param number $mobile
*/
function isMobile($mobile) {
    if (!is_numeric($mobile)) {
        return false;
    }
    return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
}

/**
 * 验证输入的邮件地址是否合法
 *
 * @param   string      $email      需要验证的邮件地址
 *
 * @return bool
 */
function is_email($user_email)
{
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
    {
        if (preg_match($chars, $user_email)){
            return true;
        }
        else{
            return false;
        }
   }
   else{
            return false;
        }
}

/*++++++bk_user_info+++++++++++++++++++图书用户信息数据表+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  +  book_uid   +  book_uacc   + book_uname + book_upass + book_ugroup  +  book_utelphone + book_uemail     +  book_uyzm  + book_uctime  + book_bakinfo  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 用户ID号码  +  用户账号名  +  用户别名  +  用户密码  + 用户类型组   +   用户手机号码  +   用户邮箱地址  +  用户邀请码 + 用户创建时间 +  用户备注信息 +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
include("sqlite3.php");
if($_GET["login"]=="out")
{
	$temp_time=time()-1800;
	setcookie("book_lyclub_uid",null,$temp_time);		//用户数据表中的ID号
	setcookie("book_lyclub_uacc",null,$temp_time);		//用户英文账号名
	setcookie("book_lyclub_name",null,$temp_time);		//用户中英显示名
	setcookie("book_lyclub_utel",null,$temp_time);		//用户的手机号码
	setcookie("book_lyclub_umail",null,$temp_time);	//用户的电子邮箱
	setcookie("book_lyclub_group",null,$temp_time);	//用户的管理组模式
	header("Location: ?login=lgform&webpage=".$_GET["webpage"]."");
}
elseif($_GET["login"]=="lgform")
{
	//用户登陆列表
	echo "<h1>用户登陆界面</h1>";
	echo "<form action='?login=login&webpage=".$_GET["webpage"]."' method='post'>";
	echo "<table border='0' cellspacing='4' cellpadding='0'>";
	echo "<tr><th>用户账号:</th><td><input type='text' id='lguser' name='lguser' value='' /></td></tr>";
	echo "<tr><th>用户密码:</th><td><input type='password' id='lgpass' name='lgpass' value='' /></td></tr>";
	echo "<tr><th colspan='2'><input type='submit' value='登录' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?login=regform'>注册普通用户</a></th></tr>";
	echo "</table></form>";
}
elseif($_GET["login"]=="login")
{
	//用户登陆处理
	if($_POST["lguser"]=="")
	{
		echo "<script>alert(\"用户账号不能为空！\");history.go(-1);</script>";
		exit();
	}
	elseif($_POST["lgpass"]=="")
	{
		echo "<script>alert(\"用户密码不能为空！\");history.go(-1);</script>";
		exit();
	}
	$scsql="select * from bk_user_info where book_uacc='".$_POST["lguser"]."' and 0<1";
	if(!$us1=$GLOBALS["db1"]->query($scsql))
	{
		echo "<script>alert(\"用户账号不存在！\");history.go(-1);</script>";
		exit();
	}
	$us2=$us1->fetchArray();
	if($us2["book_uacc"]=="")
	{
		echo "<script>alert(\"用户账号不存在！\");history.go(-1);</script>";
		exit();
	}
	elseif($us2["book_upass"]!=sha1($_POST["lgpass"]))
	{
		echo "<script>alert(\"用户密码错误！\");history.go(-1);</script>";
		exit();
	}
	$temp_time=time()+1800;
	setcookie("book_lyclub_uid",$us2["book_uid"],$temp_time);		//用户数据表中的ID号
	setcookie("book_lyclub_uacc",$us2["book_uacc"],$temp_time);		//用户英文账号名
	setcookie("book_lyclub_name",$us2["book_uname"],$temp_time);		//用户中英显示名
	setcookie("book_lyclub_utel",$us2["book_utelphone"],$temp_time);		//用户的手机号码
	setcookie("book_lyclub_umail",$us2["book_uemail"],$temp_time);	//用户的电子邮箱
	setcookie("book_lyclub_group",$us2["book_ugroup"],$temp_time);	//用户的管理组模式
	if($_GET["webpage"]==""&&$us2["book_ugroup"]=="admin"||$us2["book_ugroup"]=="super")
		echo "<script>alert(\"用户登录成功！登录时间为30分钟！\");location.href='portal/index.php';</script>";
	elseif($_GET["webpage"]=="")
		echo "<script>alert(\"用户登录成功！登录时间为30分钟！\");location.href='index.php';</script>";
	elseif($_GET["webpage"]!="")
	{
		echo "<script>alert(\"用户登录成功！登录时间为30分钟！\");location.href='".$_GET["webpage"]."';</script>";
	}
}
elseif($_GET["login"]=="regform")
{
	//用户注册列表
	echo "<h1>用户登陆界面</h1>";
	echo "<form action='?login=register&webpage=".$_GET["webpage"]."' method='post'>";
	echo "<table border='0' cellspacing='4' cellpadding='0'>";
	echo "<tr><th>用户账号:</th><td><input type='text' id='lguser' name='lguser' value='' /></td></tr>";
	echo "<tr><th>用户密码:</th><td><input type='password' id='lgpass' name='lgpass' value='' /></td></tr>";
	echo "<tr><th>确认密码:</th><td><input type='password' id='yzpass' name='yzpass' value='' /></td></tr>";
	echo "<tr><th>用户别名:</th><td><input type='text' id='lgname' name='lgname' value='' /></td></tr>";
	echo "<tr><th>手机号码:</th><td><input type='telphone' id='lgtelp' name='lgtelp' value='' /></td></tr>";
	echo "<tr><th>邮箱地址:</th><td><input type='mail' id='lgmail' name='lgmail' value='' /></td></tr>";
	echo "<tr><th colspan='2'><input type='submit' value='注册' />&nbsp;&nbsp;&nbsp;&nbsp;<a href='?login=lgform'>返回登录</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' value='重置' /></th></tr>";
	echo "</table></form>";
}
/*++++++bk_user_info+++++++++++++++++++图书用户信息数据表+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  +  book_uid   +  book_uacc   + book_uname + book_upass + book_ugroup  +  book_utelphone + book_uemail     +  book_uyzm  + book_uctime  + book_bakinfo  +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  + 用户ID号码  +  用户账号名  +  用户别名  +  用户密码  + 用户类型组   +   用户手机号码  +   用户邮箱地址  +  用户邀请码 + 用户创建时间 +  用户备注信息 +
--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
elseif($_GET["login"]=="register")
{
	//用户注册处理
	if($_POST["lguser"]==""||$_POST["lgpass"]==""||$_POST["yzpass"]==""||$_POST["lgname"]==""||$_POST["lgtelp"]==""||$_POST["lgmail"]=="")
	{
		echo "<script>alert(\"注册用户时必须全部填写！\");history.go(-1);</script>";
		exit();
	}
	elseif($_POST["lgpass"]!=$_POST["yzpass"])
	{
		echo "<script>alert(\"用户密码和确认密码不一致！\");history.go(-1);</script>";
		exit();
	}
	elseif(!is_email($_POST["lgmail"]))
	{
		echo "<script>alert(\"邮箱地址不正确！\");history.go(-1);</script>";
		exit();
	}
	elseif(!isMobile($_POST["lgtelp"]))
	{
		echo "<script>alert(\"手机号码不正确！\");history.go(-1);</script>";
		exit();
	}
	
	$nm1=$GLOBALS["db1"]->query("select max(book_uid) as maxuid from bk_user_info");
	$nm2=$nm1->fetchArray();
	$bkuid=$nm2["maxuid"];
	$bkuid=$bkuid+1;
	$insql="insert into bk_user_info values(".$bkuid.",'".$_POST["lguser"]."','".urlencode($_POST["lgname"])."','".sha1($_POST["lgpass"])."','guest','".$_POST["lgtelp"]."','".$_POST["lgmail"]."','','".date("Y-m-d H:i:s")."','')";
	if($GLOBALS["db1"]->exec($insql))
	{
		echo "<script>alert(\"用户注册成功！\");location.href='?login=lgform&webpage=".$_GET["webpage"]."';</script>";
	}
	else
	{
		echo "<script>alert(\"用户注册失败，请检查填写的信息！\");history.go(-1);</script>";
		exit();
	}
}
else
{
	header("Location: ?login=out&webpage=".$_GET["webpage"]);
}

?>
</center>
</body>
</html>