
<?php 
	if (!isset($_COOKIE["user"]))
	{
		echo "<script>alert('您还没有登录！'); history.go(-1);</script>"; 
	}
?>
<title>
    12307 车站售票管理系统——用户版
</title>
<p style="TEXT-ALIGN: center">
    <span style="COLOR: #953734"><strong><span style="FONT-SIZE: 24px; COLOR: #953734; FONT-FAMILY: 黑体, SimHei">12307 车站售票管理系统——用户版</span></strong></span>
</p>
<p>
    &nbsp;
</p>
<p style="text-align: right;">
    <a textvalue="客运首页" target="_self" href="login.html">客运首页</a> &nbsp;<a textvalue="车票预订" target="_self" href="main.php">车票预订</a> &nbsp;<a textvalue="我的信息" target="_self" href="mine.php">我的12307</a><br/>
</p>
<form name="query" method="GET">
    <!--<a href="register.php">查询</a>  -->
	<input name="submit1" value="修改密码" type="submit" />
    <input name="submit2" value="我的订单" type="submit"/>
    <input name="submit3" value="退出登录" type="submit"/>
</form>
<?php
	if(isset($_GET["submit3"]) && $_GET["submit3"] == "退出登录")  
    {  
		setcookie("user", "", time()-3600);
	}
	if(isset($_GET["submit2"]) && $_GET["submit2"] == "我的订单")  
    {  
		echo '
		<form name="alter_form" method="post">
		<p>
			如需退订，请输入订单号：&nbsp; &nbsp;&nbsp;<input name="fno" type="text"/>&nbsp;&nbsp;<input size="26" value="确认退订" name="alter" type="submit"/>
		</p>
		</form>
		';
		if(isset($_POST["alter"]) && $_POST["alter"] == "确认退订")  
		{  
			$fno = $_POST["fno"];  
			echo "<script>alert('$fno'); </script>";  
			$conn=odbc_connect('train1','sa','369568');
			if (!$conn){exit("Connection Failed: " . $conn);}
			if($fno == "" )  {  
            echo "<script>alert('请输入订单号！'); history.go(-1);</script>";  
			}  
			else{
				$sql_alter="delete from purchase
							where pno = $fno;" ;
				$rs_alter=odbc_exec($conn,$sql_alter);
				if (!$rs_alter)
					{exit("Error in SQL");}
				else{
					echo "<script>alert('退订成功'); history.go(-1);</script>"; 
				}
		        }
			odbc_close($conn);
		}
		$conn=odbc_connect('train1','sa','369568');
		if (!$conn){
			exit("Connection Failed: " . $conn);
		}
		$user_temp = $_COOKIE["user"];
		$sql="SELECT Pno,Fno,Tnum
				FROM Purchase
				WHERE username = '$user_temp'"; 
		$rs=odbc_exec($conn,$sql);
		if (!$rs)
			{exit("Error in SQL");}
	
		$rows = odbc_num_rows($rs);//获取行数 
		$colums = odbc_num_fields($rs); //获取列数 
		echo "共计".$rows."行 ".$colums."列<br/>"; 
		
		echo "<table border='1' cellspacing='0' cellpadding='5'><tr>"; 
		echo "<tr>
		<th>订单号</th>
		<th>车次</th>
		<th>订单票数</th>
		</tr>";
        echo "</tr>"; 
        while($row=odbc_fetch_row($rs)){ 
            echo "<tr>"; 
            for($i=1; $i<= $colums; $i++){ 
				$conname=odbc_result($rs,$i);
				$conname = iconv("gb2312","UTF-8//IGNORE",$conname);//编码问题一定要注意！！！！！！！！！！！！
				//echo "<script>alert('$conname')</script>";
                echo "<td>$conname</td>"; 
            } 
            echo "</tr>"; 
        } 
        echo "</table>"; 
		odbc_close($conn);
	}
	if(isset($_GET["submit1"]) && $_GET["submit1"] == "修改密码")  
    {  
		echo '
		<form name="password_form" method="post">
			<p>原密码：&nbsp;&nbsp;&nbsp;&nbsp;<input name="old" type="password"/></p>
			<p>新密码：&nbsp;&nbsp;&nbsp;&nbsp;<input name="new" type="password"/></p>
			<p>确认新密码密码：&nbsp;<input name="conf" type="password"/></p>
			<input size="26" value="确认修改" name="password" type="submit"/>
		</form>	
		';
		if(isset($_POST["password"]) && $_POST["password"] == "确认修改")  
		{  
			$old = $_POST["old"];  
			$new = $_POST["new"]; 
			$conf = $_POST["conf"]; 
			//echo "<script>alert('$new'); </script>";  
			$conn=odbc_connect('train1','sa','369568');
			if (!$conn){exit("Connection Failed: " . $conn);}
			if($old == "" || $new == ""||$conf == "")  {  
            echo "<script>alert('请确认信息完整性！'); history.go(-1);</script>";  
			}  
			else if($new != $conf){
				echo "<script>alert('两次输入的密码不同！'); history.go(-1);</script>";  
			}
			else{
				$user_temp = $_COOKIE["user"];
				$sql_pass="update users
					set password = '$new'
					where username = '$user_temp'"; 
				$rs_pass=odbc_exec($conn,$sql_pass);
				if (!$rs_pass)
					{exit("Error in SQL");}
				else{
					echo "<script>alert('密码修改成功'); history.go(-1);</script>"; 
				}
		        }
			odbc_close($conn);
		}
	}
?>