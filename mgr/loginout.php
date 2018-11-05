<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?PHP
 session_start(); 
// 这种方法是将原来注册的某个变量销毁
unset($_SESSION['admin']);
unset($_SESSION['js']); 
// 这种方法是销毁整个 Session 文件
//session_destroy(); 
echo "<script language=javascript>alert('已安全退出!');</script>"  ;
echo "<Meta http-equiv='refresh' content='0;URL=./index.php'>";

?>