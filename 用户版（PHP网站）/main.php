<!--<html>-->

<?php
// Print a cookie
if(isset($_COOKIE["user"]))
{
echo $_COOKIE["user"];
}
// A way to view all cookies
print_r($_COOKIE);
?>

<?php 
//--------------------------------------------------------------
//搜索函数----------------------------------------------------
    function ShowTable($dep,$arr){	
        $conn=odbc_connect('train1','sa','369568');
		if (!$conn){
			exit("Connection Failed: " . $conn);
		}
		$sql="SELECT Fno,Ldepart,Larrival,Ctype,Ltime,Ttime,Price,Cseat,LastTicket
				FROM Frequency,Line,Coach
				WHERE Ldepart = '$dep'  and Larrival = '$arr'  and Frequency.Cno = Coach.Cno and Frequency.Lno = Line.Lno;"; 
        
		//$sql="SELECT Fno,Ldepart,Larrival,Ctype,Ltime,Ttime,Price,Cseat,LastTicket
		//		FROM Frequency,Line,Coach";
		//$sql="INSERT INTO Coach(Cno,Cseat,Ctype) values('T33',33,'测试');";		
		$rs=odbc_exec($conn,$sql);
		if (!$rs)
			{exit("Error in SQL");}
	
		$rows = odbc_num_rows($rs);//获取行数 
		$colums = odbc_num_fields($rs); //获取列数 
		echo "共计".$rows."行 ".$colums."列<br/>"; 
        
        echo "<table border='1' cellspacing='0' cellpadding='5'><tr>"; 
		echo "<tr>
		<th>车次</th>
		<th>起始站</th>
		<th>终点站</th>
		<th>车型</th>
		<th>所需时间</th>
		<th>发车时间</th>
		<th>票价</th>
		<th>坐席总数</th>
		<th>余票</th>
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
//--------------------------------------------------------------
//--------------------------------------------------------------

//--------------------------------------------------------------
//购买函数----------------------------------------------------
	function Purchase($fno,$num,$user)
	{
		$conn=odbc_connect('train1','sa','369568');
		if (!$conn){
			exit("Connection Failed: " . $conn);
		}

		$times = date("ymd",time());//2010-08-29 11:25:26 ----------------得到时间，拼接得到Fno
		$times = '20'.$times.'001';
		//echo "<script>alert('$times'); </script>";  	
		$times =  (float)$times;
		//echo "<script>alert('$times'); </script>";  		
		$sql_times="select Max(Pno) 
				from purchase";
		$rs_times=odbc_exec($conn,$sql_times);
		if (!$rs_times){
			exit("Error in SQL");}
		else{	
			$times_temp = odbc_result($rs_times,1);
			if($times <=  $times_temp){
				$times = $times_temp + 1;
			}else{
				//$times = $times;
			}		
		}

		//echo "<script>alert('$times'); history.go(-1);</script>";  //---------------------更新购买表
		$sql_insert="insert into Purchase(Pno,Username,Fno,Tnum)
						values('$times','$user','$fno','$num')"; 
		$rs_insert=odbc_exec($conn,$sql_insert);
		if (!$rs_insert){
			exit("Error in SQL");}
		

		//$sql="SELECT Fno,Ldepart,Larrival,Ctype,Ltime,Ttime,Price,Cseat,LastTicket
		//		FROM Frequency,Line,Coach";
		//$sql="INSERT INTO Coach(Cno,Cseat,Ctype) values('T33',33,'测试');";	//---------更新查询表
		$sql="update Frequency  
				set LastTicket = LastTicket - '$num'
				where Fno = '$fno'"; 		
		$rs=odbc_exec($conn,$sql);
		if (!$rs)
			{exit("Error in SQL");}
		else
		{
			echo "<script>alert('购买成功！'); history.go(-1);</script>"; 
		}
		odbc_close($conn);		
	}
//--------------------------------------------------------------
//--------------------------------------------------------------
	if(isset($_COOKIE["user"]))
	{
	$user = $_COOKIE["user"];
	}
	if (!isset($_COOKIE["user"]))
	{
		echo "<script>alert('您还没有登录！'); history.go(-1);</script>"; 
	}?>
	
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
<form name="query" method="post">
    <!--<a href="register.php">查询</a>  -->
	出发地：<input name="depart" type="text"/>目的地：<input name="arrival" type="text"/><input name="submit" value="查询" type="submit"/>
</form>
<form name="buy" method="post">
    <!--<a href="register.php">查询</a>  -->	<br/>请输入要购买的车次：<input name="fnos" type="text"/>数量：<input name="number" type="text"/><input name="submit2" value="购票" type="submit"/>
</form>	
<?php	
	if(isset($_POST["submit"]) && $_POST["submit"] == "查询")  
    {  
        $dep = $_POST["depart"];  
        $arr = $_POST["arrival"]; 
		$dep = iconv("UTF-8","gb2312//IGNORE",$dep);
        $arr = iconv("UTF-8","gb2312//IGNORE",$arr);
		if($dep == "" || $arr == "")  
        {  
            echo "<script>alert('请输入出发城市及目的城市！'); history.go(-1);</script>";  
			//ShowTable("train"); 
        }  
        else  
        {  
			ShowTable("$dep","$arr"); 
		}  
    }  
	if(isset($_POST["submit2"]) && $_POST["submit2"] == "购票")  
    {  
		//echo "<script>alert('123'); history.go(-1);</script>";  
        $fno = $_POST["fnos"];  
        $num = $_POST["number"]; 
		$fno = iconv("UTF-8","gb2312//IGNORE",$fno);
        $num =  intval($num, 10);
		if($fno == "" || $num == "")  
        {  
            echo "<script>alert('请输入要购买的车次及票数！'); history.go(-1);</script>";  
			//ShowTable("train"); 
        }  
        else  
        {  
			Purchase("$fno","$num","$user"); 
		}  
    }  
    //else  
    //{  
    //    echo "<script>alert('提交未成功！'); history.go(-1);</script>";  
    //}  
        
?>

<!--</html>-->