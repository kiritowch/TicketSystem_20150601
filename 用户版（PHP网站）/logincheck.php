<?php 
          
    if(isset($_POST["submit"]) && $_POST["submit"] == "登陆")  
    {  
        $user = $_POST["username"];  
        $psw = $_POST["password"]; 
                session_start();//启动会话
                $code=$_POST["check"];          
        if($user == "" || $psw == ""||$code == "")  
        {  
            echo "<script>alert('请输入用户名/密码/验证码！'); history.go(-1);</script>";  
        }  
        else if($code!=$_SESSION["check"])  
                {  
                        echo"<script>alert('$code');</script>"; 
                        $temp1=$_SESSION["check"];
                        echo"<script>alert('$temp1');</script>"; 
                        echo"<script>alert('验证码错误！'); history.go(-1);</script>";  
                }  
                else  
        {  
	try{
			$cnx = new PDO("odbc:Driver={SQL Server};Server=CC-93C3E920125C\CC;Database=train;",'sa','369568'); 
	}catch(PDOException $e){
			exit("Connection Failed: " . $e->getMessage());
	}
	//var_dump($cnx);  此函数用来准确调试
	
	//第一种防护手段-------------------------------------------------------------------------------------------------------------------------
	/*$pdo=new PDO($dsn,$user,$pwd);  // 连接数据库               -------------------------------------代码示例
	$query="INSERT INTO tb_chengji SET xuesheng=:xuesheng,yuwen=:yuwen";
	$result=$pdo->prepare($query);
	$result->execute(array(':xuesheng'=>'赵天平',':yuwen'=>'90'));  // 执行一次
	$result->execute(array(':xuesheng'=>'张冬雪',':yuwen'=>'115')); // 再执行一次*/
	//--------------------------------------------------------------------------------------------------------------------------------------------
	$sql = "select username,password from users where username = :username and password = :password";//PDO最大限度防止SQL注入问题
	$rs  =  $cnx -> prepare ( "$sql" );
	$rs -> bindParam ( ':username' ,  $_POST[username] );
	$rs -> bindParam ( ':password' ,  $_POST[password] );
	$rs->execute();
		
	if (!$rs){
			exit("Error in SQL");
	}
	$allrows = $rs->fetchAll(PDO::FETCH_ASSOC);       //以关联下标从结果集中获取所有数据 
            if($allrows)  {
                                echo "<script>alert('欢迎进入');history.go(-1);</script>";  //不显示了..
                        
                                setcookie("user", $_POST["username"], time()+3600);//设置cookie
                                
                                header("location: main.php");
            }else{  
                echo "<script>alert('用户名或密码不正确！');history.go(-1);</script>";  
            }
                        odbc_close($conn);                      
        }  
    }  
    else  
    {  
        echo "<script>alert('提交未成功！'); history.go(-1);</script>";  
    }  
  
?>