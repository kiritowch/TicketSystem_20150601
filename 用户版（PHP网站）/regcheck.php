<?php  
    if(isset($_POST["Submit"]) && $_POST["Submit"] == "注册")  
    {  
        $user = $_POST["username"];  
        $psw = $_POST["password"];  
        $psw_confirm = $_POST["confirm"];  
        if($user == "" || $psw == "" || $psw_confirm == "")  
        {  
            echo "<script>alert('请确认信息完整性！'); history.go(-1);</script>";  
        }  
        else  
        {  
            if($psw == $psw_confirm)  
            {  
                 $conn=odbc_connect('train1','sa','369568');
				if (!$conn){
					exit("Connection Failed: " . $conn);
				}
                //mysql_query("set names 'gdk'"); //设定字符集  
                $sql = "select username from users where username = '$_POST[username]'"; //SQL语句  
                $rs = odbc_exec($conn,$sql);      //执行SQL语句  
                if (!$rs){
				exit("Error in SQL");
				} 
                if(odbc_fetch_row($rs))   //如果已经存在该用户  
                {  
                    echo "<script>alert('用户名已存在'); history.go(-1);</script>";  
                }  
                else    //不存在当前注册用户名称  
                {  
                    $sql_insert = "insert into users (username,password) values('$_POST[username]','$_POST[password]')";  
					//$sql_insert = "insert into users (username,password) values('admin1','123')";  
                    $rs = odbc_exec($conn,$sql_insert);      //执行SQL语句  
					//odbc_error函数：获取最后的错误代码
					//odbc_errormsg函数：获取最后的错误信息
					if (!$rs){
						//exit("Error in SQL"); 
						echo "<script>alert('系统繁忙，请稍候！'); history.go(-1);</script>";  
					}   
                    else{  
                        echo "<script>alert('注册成功！'); history.go(-1);</script>";  
						//$message .= '以下错误信息由ODBC 提供:'. odbc_errormsg();  
						//echo "<script>alert('$message');</script>"; 
                    }  

                } 
				odbc_close($conn);				
            }  
            else  
            {  
                echo "<script>alert('密码不一致！'); history.go(-1);</script>";  
            }  
        }  
    }  
    else  
    {  
        echo "<script>alert('提交未成功！'); history.go(-1);</script>";  
    }  
?>  